<?php


/**
 * @author Thomas Urban <info@toxa.de>
 * @copyright 2010, toxA IT-Dienstleistungen, Berlin
 * @license GPL
 */


if ( !defined( 'TL_ROOT' ) )
	die( 'You can not access this file directly!' );


class toxa_recent extends Frontend
{

	// name of template to use
	protected $strTemplate = 'recent_articles';

	// number of matches to list at most by default
	protected $intMaxCount = 3;

	// default page section to look for matching articles in, only
	protected $focusOnColumn = 'main';



	public static function processInsertTag( $strTag )
	{
		$parameters = explode( '::', $strTag );
		$name       = array_shift( $parameters );

		switch ( $name )
		{
			case 'recent' :
			case 'recent_articles' :
				$instance = new self();
				return call_user_func_array( array( &$instance, 'renderRecentArticles' ), $parameters );

			case 'recent_articles_list' :
				$instance = new self();
				$instance->strTemplate = 'recent_articles_list';
				return call_user_func_array( array( &$instance, 'renderRecentArticles' ), $parameters );
		}

		return false;
	}


	protected function renderRecentArticles( $intMaxCount = 3, $focusOnColumn = 'main' )
	{
		$this->intMaxCount   = intval( $intMaxCount );
		$this->focusOnColumn = trim( $focusOnColumn );

		return $this->generate();
	}

	protected function generate()
	{
		$this->Template = new FrontendTemplate( $this->strTemplate );

		$this->compile();

		return $this->Template->parse();
	}

	protected function compile()
	{
		$intMaxCount   = intval( $this->intMaxCount );
		$focusOnColumn = trim( $this->focusOnColumn );


		// compile complex condition used to select recent articles
		$conditions = array();
		$parameters = array();

		//  - must have enabled teaser
		$conditions[] = '( a.showTeaser<>0 )';
		$conditions[] = '( a.published<>0 )';

		//  - must be currently published
/*		if ( !BE_USER_LOGGED_IN ) */ // BE_USER_LOGGED_IN seems to be always false, though it's not checking for authorization anyway, so ...
		{
			$time = time();
			$conditions[] = $publicCondition = "( a.published<>0 ) AND ( ( a.start='' OR a.start<=$time ) AND ( a.stop='' OR a.stop>=$time ) )";
		}

		//  - must be associated with selected section of page
		if ( $focusOnColumn )
		{
			$conditions[] = '( a.inColumn=? )';
			$parameters[] = $focusOnColumn;
		}


		// compile statement used to select all information on matching recent articles
		$conditions      = count( $conditions ) ? ' WHERE ' . implode( ' AND ', $conditions ) : '';
		$sharedCondition = $publicCondition ? preg_replace( '/a\./', 'a2.', $publicCondition ) : '1';

		$statement  = $this->Database->prepare( <<<EOT
SELECT
	a.*,
	IF(a.start<>'',a.start,IF(MAX(c.tstamp)>a.tstamp,MAX(c.tstamp),a.tstamp)) AS sorting,
	COUNT(DISTINCT a2.id) AS articles_in_column
FROM
	tl_article a
		LEFT JOIN tl_content c
			ON ( c.pid=a.id )
		LEFT JOIN tl_article a2
			ON ( a2.pid=a.pid AND a2.inColumn=a.inColumn AND $sharedCondition )
$conditions
GROUP BY
	a.id
ORDER BY
	sorting DESC
EOT
											);

		// result set might include active article to be actually excluded from
		// list in template --> query to get one more than requested
		$statement->limit( $intMaxCount + 1 );


		// query database for list
		$list     = call_user_func_array( array( &$statement, 'execute' ), $parameters );
		$teasers  = array();

		// get currently selected page/article
		$currentPageId    = $this->getPageIdFromUrl();
		$currentArticleId = trim( $_GET['articles'] );


		// traverse list and collect/compile all interesting information for template
		while ( $list->next() )
		{
			$page = $this->Database->prepare( 'SELECT id, alias, published FROM tl_page WHERE id=? AND published<>0' )
							->limit( 1 )
							->execute( $list->pid );

			if ( $page->numRows > 0 )
			{
				$cssID            = deserialize( $list->cssID, true );
				$alias            = strlen( $list->alias ) ? $list->alias : $list->title;
				$articleAliasOrId = (!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($list->alias)) ? $list->alias : $list->id;

				// compile all flavours of references
				$pageHref    = $this->generateFrontendUrl( $page->row() );
				$articleHref = $this->generateFrontendUrl( $page->row(), '/articles/' . $articleAliasOrId );
				$focusHref   = $pageHref . '#' . $articleAliasOrId;

				$teasers[] = array(
								'articleId'    => $list->id,
								'id'           => strlen( $cssID[0] ) ? strlen( $cssID[0] ) : standardize( $alias ),
								'selector'     => $articleAliasOrId,
								'link'         => $list->title,
								'title'        => specialchars( $list->title ),
								'more'         => specialchars( sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $list->title ) ),
								'headline'     => $list->title,
								'teaser'       => $list->teaser,
								'href'         => ( ( $list->articles_in_column > 1 ) || ( $focusOnColumn != 'main' ) ) ? $focusHref : $pageHref,
								'page_href'    => $pageHref,
								'article_href' => $articleHref,
								'focus_href'   => $focusHref,
								'is_active'    => ( $currentPageId == ( is_numeric( $currentPageId ) ? $page->id : $page->alias ) )
													&&
												  ( !$currentArticleId || ( $currentArticleId == ( is_numeric( $currentArticleId ) ? $list->id : $list->alias ) ) ),
								);
			}
		}


		// prepare template context
		$this->Template->teasers   = $teasers;
		$this->Template->max_count = $intMaxCount;
		$this->Template->more      = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}

