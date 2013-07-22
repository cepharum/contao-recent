<?php

$teasers = array_slice( array_filter( $this->teasers, create_function( '$a', 'return !$a["is_active"];' ) ), 0, $this->max_count );

if ( count( $teasers ) )
{
?>
<div class="recent-articles">
<?php
	foreach ( $teasers as $teaser )
	{
?>
	<div class="article">
		<h2><?php echo $teaser['headline'] ?></h2>
		<div class="teaser">
			<?php echo $teaser['teaser'] ?>
		</div>
		<div class="more">
			<a href="<?php echo $teaser['href'] ?>" title="<?php echo $teaser['more'] ?>"><?php echo $this->more ?></a>
		</div>
	</div>
<?php 
	}
?>
</div>
<?php
}
