<?php
set_meta('page_id', 'association-profile');
set_meta('full_url', url_factory('trade_association', array('assoc_abbr' => $org->abbr, 'assoc_name' => $org->name)));
set_meta('subnav', 'none');
set_meta('title', $org->name .' Members');

set_meta('breadcrumbs','
	<a href="/">Home</a>
	<a href="/bookstores/">Bookstores</a>
	<a href="/book-trade-association/">Professional Bookseller Associations</a>".
	$org->name
');
set_meta('description', $org->name .' Members on '. $GLOBALS['SITE_NAME']);
?>

<div class="page-body has-sidebar postlaunch">
	<div id="primary" class="main-content">

		<header class="page-head">
			<h1><?php echo $org->name; ?></h1>
			<?php if ($org->logo_large) { ?>
				<div class="img-type-1"><img src="/images/content_images/associations/<?php echo $org->logo_large ?>" alt="<?=$org->name?> logo"></div>
			<?php } ?>
			<p id="description" <?php if ($GLOBALS['is_site_editor']){ echo 'data-mode="inline" data-pk="'. $org->id .'" data-type="wysihtml5" data-toggle="manual" data-original-title="Edit Description" '; } ?>>
				<?php echo $org->description; ?> Visit <a href="<?php echo prep_url($org->url); ?>"  target="_blank"><?php echo $org->name; ?> online</a>
				<?php if ($GLOBALS['is_site_editor']){ echo ' <a href="#" id="pencil"><i class="glyphicon glyphicon-pencil"style="padding-right: 5px"></i>[edit]</a>'; } ?>
			</p>			
		</header>
	    <div class="summary bookseller">

			<?php
				$count = 0;
				foreach($members as $member){
					$count++;
					$url = url_factory("bookstore", array("dealer_id" => $member['id'],"business_name" => $member['business_name']));
					?>
					<div class="item">
						<?php if ( $member['logo'] || $member['photo'] ) { ?>
							<div class="img-type-1">
								<a href="<?=$url?>">
									<?php if ($member['logo']){ ?>
										<img src="<?php echo cloudObject($member['logo']); ?>" alt="<?=cleanPhrase($member['name'])?> logo">							
									<?php } elseif ($member['photo']){ ?>
										<img src="<?php echo cloudObject($member['photo']); ?>" alt="photo of <?=cleanPhrase($member['name'])?>">
									<?php } ?>
								</a>
							</div>
						<?php } ?>
						<div class="">
							<h3><a href="<?=$url?>"><?=$member['business_name']?></a></h3>
							
							<?php 
								if ($member['open'] == 'y') {
									echo '<p class="bm-store">We also have a brick-and-mortar store! Visit us in:</p>';
								}
								echo '<p class="location"><em>' . $member['city'] . ', ' . $member['state_name'] . ', ' . $member['country_name'] . '</em></p>';
							?>
							<p><?php echo word_limiter(strip_tags($member['description']),40); ?></p>
			
							<?php if ($member['specialties']) { ?>
								<h5>Specializing in:</h5>
								<ul class="terms">
									<?php foreach($member['specialties'] as $special) {?>
										<li><a href="<?php echo $special['url'];?>"><?php echo word_limiter($special['name'],4); ?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
							<div class="more"><a href="<?=$url?>">More Info</a></div>
						</div>
					</div>
	
			<?php } ?>							

		</div><!-- /.summary -->

	</div>
	
	<div id="secondary" class="sidebar">
     
		<?php if ($org->display_search == '1'){ ?>
			<div class="item boxed">
				<h3>Search the inventory of <?php echo $org->name; ?> Members</h3>
				<form action="/search.php" class="tighter">
					<div class="form-group">
						<label for="author-sb" class="speech">Author</label>
						<input type="text" class="form-control" name="author" id="author-sb" placeholder="Author">
					</div>
					<div class="form-group">
						<label for="title-sb" class="speech">Title</label>
						<input type="text" class="form-control" name="title" id="title-sb" placeholder="Title">
					</div>
					<div class="form-group">
						<label for="keyisbn-sb" class="speech">Keyword or ISBN</label>
						<input type="text" class="form-control" name="keyisbn" id="keyisbn-sb" placeholder="Keyword or ISBN">
					</div>
					<input type="hidden" name="assoc" value="<?php echo $org->abbr; ?>">
					<div class="form-group">
						<button type="submit" class="btn type-1">Find Books</button>
					</div>
				</form>				
			</div>			
		<?php } ?>

		<?=$subnav?>

	</div>
</div>
