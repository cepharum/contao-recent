<?php

$teasers = array_slice( $this->teasers, 0, $this->max_count );
if ( count( $teasers ) )
{

?>
<div class="recent-articles-list">
	<ul>
<?php
	foreach ( $teasers as $teaser )
	{
?>
		<li>
<?php
		if ( $teaser['is_active'] )
		{
?>
			<span class="active"><?php echo $teaser['headline'] ?></span>
<?php
		}
		else
		{
?>
			<a href="<?php echo $teaser['href'] ?>" title="<?php echo $teaser['more'] ?>"><?php echo $teaser['headline'] ?></a>
<?php
		}
?>
		</li>
<?php 
	}
?>
	</ul>
</div>
<?php
}

