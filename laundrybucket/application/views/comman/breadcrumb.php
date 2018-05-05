<div id="breadcrumb"> 
	<a href="<?= base_url(); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<?php
	$menuIcons = $this->session->userdata('menuIcons');
	
		if(!empty($breadcrumb))
		{
			foreach($breadcrumb as $link)
			{
				if( $link['url'] != '' || $link['url'] != NULL )
				{
				    if($link['title'] == 'Classes')
                    {
                        $iconName = substr($link['title'],0,-2);
                    }else{
                        $iconName = substr($link['title'],0,-1);
                    }
	?>
		<a href="<?= $link['url']; ?>" title="Go <?= $link['title']; ?>" class="tip-bottom"><i class="<?= !empty($menuIcons[$iconName]) ? $menuIcons[$iconName] : ''; ?>"></i> <?= $link['title']; ?></a>
	<?php
				}else{
					echo "<a class='current' >".$link['title']."</a>";
				}
			}
		}
		
	?> 
</div>