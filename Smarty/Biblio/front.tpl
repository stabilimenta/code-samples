{assign var="page_id" value="homepage"}

{capture assign="additional_headers"}
	{literal}

		<script type="text/javascript">
			$jq('document').ready(function() {
				/* RAREBOOKS */
				var rbr_id = 479;
				var rbr_limit = 7;
				var rbr_order = 'date_added desc';
				var rb_target = '#rare-books ul';
				
				// add a spinner while this loads
				$jq(rb_target).html('<li class="loading"><div class="spinner"></div><h4>Loading content&hellip;</h4></li>');

				$jq.getJSON('/app/jaxie/fetch_rarebook_carousel/' + rbr_id + '/' + rbr_limit + '/json'+ '/' + rbr_order , function( data ) {
					var rb_html = '';
				    $jq.each(data, function (key, val) {
				        rb_html += '<li>';
				        if (val.photo) {
					        var rb_image = val.photo;
				        } else {
					        var rb_image = '{/literal}{cloudObject object="/i/en20/no-book-image.png"}{literal}';
				        }
						rb_html += '<div class="image"><a href="' + val.canonical_book_link + '"><img src="' + rb_image + '" alt="' + val.title + '"></a></div>';
						rb_html += '<div class="text">';
						rb_html += '<h4 class="title"><a href="' + val.canonical_book_link + '">' + val.title + '</a></h4>';
						rb_html += '<div class="author">' + val.author + '</div>';
						rb_html += '</div>';
						rb_html += '</li>';  
				    });
				    $jq(rb_target).html(rb_html);
				});					

				/* BIBLIO FAVS
					Not being used currently, but was adding dynamic content from three categories to tabbed sections below
					
				var bibfav_cats = new Array("24", "28", "46");
				var bibfav_limit = 7;

				$jq.each(bibfav_cats, function( index, value ) {
					var i = index + 1;
					var container = '#bibliofavs' + i;
					var tab_target = '#bibliofavs' + i + '-tab';
					var ul_target = '#bibliofavs' + i + ' ul';
				
					// add a spinner while this loads
					$jq(ul_target).html('<li class="loading"><div class="spinner"></div><h4>Loading content&hellip;</h4></li>');
						
					$jq.getJSON('/app/jaxie/fetch_list_carousel/' + value + '/' + bibfav_limit + '/json', function( data ) {
					    var bibfav_section_title = data[0].list_name;
					    $jq(tab_target).html(bibfav_section_title);
						var bibfav_html = '';
					    $jq.each(data, function (key, val) {
					        bibfav_html += '<li>';
					        if (val.photo) {
						        var bibfav_img = val.photo;
					        } else {
						        var bibfav_img = '{/literal}{cloudObject object="/i/en20/no-book-image.png"}{literal}';
					        }
							bibfav_html += '<div class="image"><img src="' + bibfav_img + '" alt="' + val.title + '"></div>';
							bibfav_html += '<div class="text">';
							if (val.link) {
								bibfav_html += '<h4 class="title"><a href="' + val.link + '">' + val.title + '</a></h4>';
							} else {
								bibfav_html += '<h4 class="title">' + val.title + '</h4>';
							}
							bibfav_html += '<div class="author">' + val.author + '</div>';
							bibfav_html += '</div>';
							bibfav_html += '</li>';  
					    });
					    $jq(ul_target).html(bibfav_html);
					    var bibfav_canonical_link = data[0].list_canonical;
					    $jq(container).append('<div class="btn wide"><a href="{/literal}{$GLOBALS.SITE_BASE}{literal}' + bibfav_canonical_link + '">View All</a></div>');
					});	
				});
				
				*/
			});
		</script>
	{/literal}
{/capture}

<section id="home-banner">
	<div id="banner-search" class="page-width">
		<form action="/search.php">
			<h1>Search for Used, Rare, and <span class="nowrap">Out-of-Print</span> Books from Independent Booksellers</h1>
			<div class="wrapper">
				<div class="item">
					<label class="speech" for="bnr-author">Author</label>
					<input type="search" id="bnr-author" name="author" value="" placeholder="Author">
				</div>
				<div class="item">
					<label class="speech" for="bnr-title">Title</label>
					<input type="search" id="bnr-title" name="title" value="" placeholder="Title">
				</div>
				<div class="item">
					<label class="speech" for="bnr-keyisbn">Keywords/ISBN</label>
					<input type="search" id="bnr-keyisbn" name="keywords" value="" placeholder="Keywords or ISBN">
				</div>
				<div class="options">
					<div class="item">
						<input type="checkbox" name="format" value="hardcover" id="format">
						<label for="format">Hardcover</label>
					</div>
					<div class="item">
						<input type="checkbox" name="first" id="first">
						<label for="first">First Edition</label>
					</div>
					<div class="item">
						<input type="checkbox" name="signed" id="signed">
						<label for="signed">Signed</label>
					</div>
					<div class="item">
						<input type="checkbox" name="order" value="pricedesc" id="order">
						<label for="order">Highest Price First</label>
					</div>
				</div>
				<div class="submit">
					<input type="submit" value="Find Books" class="btn primary">
					<a href="/usedbooksearch.bib" class="adv-search">Advanced Search</a>
				</div>
				<input type="hidden" name="stage" value="1">
			</div>
		</form>
	</div>	
	<div id="biblio-carousel" class="carousel slide" data-ride="carousel">
		<ul class="carousel-inner">
			<li id="outdoors-slide" class="carousel-item active">
				<a href="/rare-books/sports_games_and_recreation/s/226" class="box-link" aria-label="Visit our Rare Book Room devoted to the outdoors and sports">
					<div class="image">
						<div class="inner page-width">
							<img sizes="(min-width: 1170px) 1170px,
										(min-width: 576px) 100vw,
										130vw"
								srcset="
									{cloudObject object='/i/en20/homepage-slides/outdoors-books-375w.jpg'} 375w,
									{cloudObject object='/i/en20/homepage-slides/outdoors-books-540w.jpg'} 540w,
									{cloudObject object='/i/en20/homepage-slides/outdoors-books-768w.jpg'} 768w,
									{cloudObject object='/i/en20/homepage-slides/outdoors-books-1170w.jpg'} 1170w,
									{cloudObject object='/i/en20/homepage-slides/outdoors-books-2340w.jpg'} 2340w"
								src="{cloudObject object='/i/en20/homepage-slides/outdoors-books-1170w.jpg'}"
								alt="Books about the great outdoors and sports"
								width="1170"
								height="460"
								>
						</div>
					</div>				
					<div class="text-wrapper page-width">
						<div class="slide-text">
							<h3 class="slide-title">The Great Outdoors</h3>
							<div class="slide-caption">Whether you enjoy camping, fly fishing in a mountain stream, or just tramping through the wilds, nothing stirs the soul like time in nature. There's a wealth of beautiful books on these topics for you to explore.</div>
							<div class="btn">Learn More</div>
						</div>
					</div>
				</a>
			</li>
			<li id="slide-mod-firsts" class="carousel-item">
				<a href="/book-collecting/by-year/" class="box-link">
					<div class="image">
						<div class="inner page-width">
							<img sizes="(min-width: 1170px) 1170px,
										(min-width: 576px) 100vw,
										130vw"
								srcset="
									{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-375.jpg'} 375w,
									{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-540.jpg'} 540w,
									{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-768.jpg'} 768w,
									{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-1170.jpg'} 1170w,
									{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-2340.jpg'} 2340w"
								src="{cloudObject object='/i/en20/homepage-slides/modern-first-editions-james-bond-1170.jpg'}"
								alt="Modern First Editions are also prized by book collectors, such as this collection of James Bond novels"
								width="1170"
								height="460"
								>
						</div>
					</div>				
					<div class="text-wrapper  page-width">
						<div class="slide-text">
							<h3 class="slide-title">Bookish Eye Candy!</h3>
							<div class="slide-caption">Modern Firsts are highly collectible books from the 20th century, often featuring gorgeous cover art and prized for their dust jackets. See our picks for most desirable modern firsts in our Book Collecting By The Year series.</div>
							<div class="btn">Learn More</div>
						</div>
					</div>
				</a>
			</li>
			<li id="slide-sangorski" class="carousel-item">
				<a href="/book-collecting/what-to-collect/sangorski-sutcliffe-bookbinders/" class="box-link" aria-label="Learn about collecting Sangorski and Sutcliffe bound books">
					<div class="image">
						<div class="inner page-width">
							<img sizes="(min-width: 1170px) 1170px,
										(min-width: 576px) 100vw,
										130vw"
								srcset="
									{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-375.jpg'} 375w,
									{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-540.jpg'} 540w,
									{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-768.jpg'} 768w,
									{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-1170.jpg'} 1170w,
									{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-2340.jpg'} 2340w"
								src="{cloudObject object='/i/en20/homepage-slides/rare-books-sangorski-sutcliffe-1170.jpg'}"
								alt="collecting rare books with special bindings, like Sangorski and Sutcliffe"
								width="1170"
								height="460"
								>
						</div>
					</div>				
					<div class="text-wrapper  page-width">
						<div class="slide-text">
							<h3 class="slide-title">Simply Stunning</h3>
							<div class="slide-caption">Works put out by the Sangorski &amp; Sutcliffe bookbinders are lavish and elegant. They created &ldquo;The Great Omar&rdquo; &mdash; a jeweled book that went down with the Titanic.</div>
							<div class="btn">Learn More</div>
						</div>
					</div>
				</a>
			</li>
			<li id="slide-about" class="carousel-item">
				<a href="/company/" class="box-link" aria-label="Learn about what kind of company Biblio is">
					<div class="image">
						<div class="inner page-width">
							<img sizes="(min-width: 1170px) 1170px,
										(min-width: 576px) 100vw,
										130vw"
								srcset="
									{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-375.jpg'} 375w,
									{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-540.jpg'} 540w,
									{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-768.jpg'} 768w,
									{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-1170.jpg'} 1170w,
									{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-2340.jpg'} 2340w"
								src="{cloudObject object='/i/en20/homepage-slides/about-biblio-animation-1170.jpg'}"
								alt="About Biblio"
								width="1170"
								height="460"
								>
						</div>
					</div>				
					<div class="text-wrapper  page-width">
						<div class="slide-text">
							<h3 class="slide-title">For the Love of Books</h3>
							<div class="slide-caption">At Biblio, we are committed to putting our book-buying customers and booksellers first, to providing a compassionate and supportive work environment, and to donating to global environmental and literacy initiatives.</div>
							<div class="btn">Learn More</div>
						</div>
					</div>
				</a>
			</li>
		</ul>
		<ul class="carousel-direction-nav">
			<li class="prev">
				<a class="carousel-control-prev" href="#biblio-carousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
			</li>				
			<li class="next">
				<a class="carousel-control-next" href="#biblio-carousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</li>				
		</ul>
		<ol class="carousel-indicators">
			<li data-target="#biblio-carousel" data-slide-to="0" class="active"></li>
			<li data-target="#biblio-carousel" data-slide-to="1"></li> 
			<li data-target="#biblio-carousel" data-slide-to="2"></li>
			<li data-target="#biblio-carousel" data-slide-to="3"></li>
		</ol>
	</div>
</section>

<section id="blog-about" class="page-width three-columns">
	{getTemplate file='partials/box-uncommonly-good-books.tpl'}
	{getTemplate file='partials/box-bibliophiles-club.tpl'}
	{getTemplate file='partials/box-social-responsibility.tpl'}
</section>

<section class="page-width" id="biblio-favorites">
	<h2>Biblio Favorites</h2>
	<ul class="nav nav-tabs type-1" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link active" id="bibliofavs1-tab" data-toggle="tab" href="#bibliofavs1" role="tab" aria-controls="bibliofavs1" aria-selected="true">Fiction</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="bibliofavs2-tab" data-toggle="tab" href="#bibliofavs2" role="tab" aria-controls="bibliofavs2" aria-selected="false">Non-Fiction</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="bibliofavs3-tab" data-toggle="tab" href="#bibliofavs3" role="tab" aria-controls="bibliofavs3" aria-selected="false">Children&rsquo;s Books</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade show active" id="bibliofavs1" role="tabpanel" aria-labelledby="bibliofavs1-tab">
			<div class="horiz-scroll books">	
				<ul>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/look-homeward-angel-by-wolfe-thomas/work/1835"><img src="https://d3525k1ryd2155.cloudfront.net/h/588/976/1305976588.0.m.jpg" width="200" alt="Look Homeward, Angel"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/look-homeward-angel-by-wolfe-thomas/work/1835">Look Homeward, Angel</a></h4>
							<div class="author">Thomas Wolfe</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/the-handmaids-tale-by-atwood-margaret/work/1198"><img src="https://d3525k1ryd2155.cloudfront.net/h/848/059/1397059848.0.m.jpg" width="200" alt="The Handmaid's Tale"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/the-handmaids-tale-by-atwood-margaret/work/1198">The Handmaid's Tale</a></h4>
							<div class="author">Margaret Atwood</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/the-haunting-of-hill-house-by-jackson-shirley/work/14697"><img src="https://d3525k1ryd2155.cloudfront.net/h/034/151/1240151034.0.m.jpg" width="200" alt="The Haunting of Hill House"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/the-haunting-of-hill-house-by-jackson-shirley/work/14697">The Haunting of Hill House</a></h4>
							<div class="author">Shirley Jackson</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/fear-and-loathing-in-las-by-thompson-hunter-s/work/4272"><img src="https://d3525k1ryd2155.cloudfront.net/h/760/799/1120799760.0.m.jpg" width="200" alt="Fear and Loathing in Las Vegas"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/fear-and-loathing-in-las-by-thompson-hunter-s/work/4272">Fear and Loathing in Las Vegas</a></h4>
							<div class="author">Hunter S Thompson</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/i-know-why-the-caged-bird-by-angelou-maya/work/3068"><img src="https://d3525k1ryd2155.cloudfront.net/h/936/113/1335113936.0.m.1.jpg" width="200" alt="I Know Why the Caged Bird Sings"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/i-know-why-the-caged-bird-by-angelou-maya/work/3068">I Know Why the Caged Bird Sings</a></h4>
							<div class="author">Maya Angelou</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/the-three-musketeers-by-dumas-alexandre/work/3218"><img src="https://d3525k1ryd2155.cloudfront.net/h/769/839/1396839769.0.m.jpg" width="200" alt="The Three Musketeers"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/the-three-musketeers-by-dumas-alexandre/work/3218">The Three Musketeers</a></h4>
							<div class="author">Alexandre Dumas</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/lonesome-dove-by-mcmurtry-larry/work/2476"><img src="https://d3525k1ryd2155.cloudfront.net/h/134/611/1139611134.0.m.jpg" width="200" alt="Lonesome Dove"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/lonesome-dove-by-mcmurtry-larry/work/2476">Lonesome Dove</a></h4>
							<div class="author">Larry McMurtry</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="tab-pane fade" id="bibliofavs2" role="tabpanel" aria-labelledby="bibliofavs2-tab">
			<div class="horiz-scroll books">	
				<ul>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/carrying-the-fire-by-collins-michael-lindbergh/work/2924568"><img src="https://d3525k1ryd2155.cloudfront.net/f/760/537/9780374537760.OL.0.m.jpg" width="200" alt="Carrying the Fire"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/carrying-the-fire-by-collins-michael-lindbergh/work/2924568">Carrying the Fire</a></h4>
							<div class="author">Michael Collins</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/seven-years-in-tibet-by-harrer-heinrich/work/65700"><img src="https://d3525k1ryd2155.cloudfront.net/h/787/176/1368176787.0.m.jpg" width="200" alt="Seven Years in Tibet"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/seven-years-in-tibet-by-harrer-heinrich/work/65700">Seven Years in Tibet</a></h4>
							<div class="author">Heinrich Harrer</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/art-of-war-by-tzu-sun/work/1962"><img src="https://d3525k1ryd2155.cloudfront.net/h/781/073/1348073781.0.m.jpg" width="200" alt="The Art of War"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/art-of-war-by-tzu-sun/work/1962">The Art of War</a></h4>
							<div class="author">Sun Tzu</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/a-vindication-of-the-rights-by-wollstonecraft-mary/work/20327"><img src="https://d3525k1ryd2155.cloudfront.net/h/286/031/1305031286.0.m.jpg" width="200" alt="A Vindication of the Rights of Women"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/a-vindication-of-the-rights-by-wollstonecraft-mary/work/20327">A Vindication of the Rights of Women</a></h4>
							<div class="author">Mary Wollstonecraft</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/between-the-world-and-me-by-coates-ta-nehisi/work/3853782"><img src="https://d3525k1ryd2155.cloudfront.net/h/310/252/1340252310.0.m.jpg" width="200" alt="Between The World And Me"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/between-the-world-and-me-by-coates-ta-nehisi/work/3853782">Between The World And Me</a></h4>
							<div class="author">Ta-Nehisi Coates</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/mastering-the-art-of-french-by-child-julia-bertholle/work/90447"><img src="https://d3525k1ryd2155.cloudfront.net/h/436/870/1397870436.0.m.jpg" width="200" alt="Mastering the Art of French Cooking"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/mastering-the-art-of-french-by-child-julia-bertholle/work/90447">Mastering the Art of French Cooking</a></h4>
							<div class="author">Julia Child</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/notes-of-a-native-son-by-baldwin-james/work/28252"><img src="https://d3525k1ryd2155.cloudfront.net/h/707/832/1032832707.0.m.jpg" width="200" alt="Notes of a Native Son"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/notes-of-a-native-son-by-baldwin-james/work/28252">Notes of a Native Son</a></h4>
							<div class="author">James Baldwin</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="tab-pane fade" id="bibliofavs3" role="tabpanel" aria-labelledby="bibliofavs3-tab">
			<div class="horiz-scroll books">	
				<ul>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/charlottes-web-by-white-e-b/work/18418"><img src="https://d3525k1ryd2155.cloudfront.net/h/528/507/1287507528.0.m.jpg" width="200" alt="Charlotte's Web"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/charlottes-web-by-white-e-b/work/18418">Charlotte's Web</a></h4>
							<div class="author">E. B. White</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/peter-and-wendy-by-barrie-j-m/work/22872"><img src="https://d3525k1ryd2155.cloudfront.net/h/908/211/1388211908.0.m.jpg" width="200" alt="Peter And Wendy"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/peter-and-wendy-by-barrie-j-m/work/22872">Peter And Wendy</a></h4>
							<div class="author">J. M. Barrie</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/anne-of-green-gables-by-montgomery-l-m/work/6786"><img src="https://d3525k1ryd2155.cloudfront.net/h/379/151/956151379.0.m.jpg" width="200" alt="Anne of Green Gables"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/anne-of-green-gables-by-montgomery-l-m/work/6786">Anne of Green Gables</a></h4>
							<div class="author">L. M. Montgomery</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/book/bear-called-paddington-bond-michael/d/1339863314"><img src="https://d3525k1ryd2155.cloudfront.net/h/314/863/1339863314.3.m.jpg" width="200" alt="A Bear Called Paddington"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/bear-called-paddington-bond-michael/d/1339863314">A Bear Called Paddington</a></h4>
							<div class="author">Michael Bond</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/bridge-to-terabithia-by-paterson-katherine/work/14224"><img src="https://d3525k1ryd2155.cloudfront.net/h/256/451/1345451256.0.m.jpg" width="200" alt="Bridge to Terabithia"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/bridge-to-terabithia-by-paterson-katherine/work/14224">Bridge to Terabithia</a></h4>
							<div class="author">Katherine Paterson</div>
						</div>
					</li>
					<li>
						<div class="image"><a href="{$GLOBALS.SITE_BASE}/the-phantom-tollbooth-by-juster-norton/work/24826"><img src="https://d3525k1ryd2155.cloudfront.net/h/096/142/703142096.0.m.jpg" width="200" alt="The Phantom Tollbooth"></a></div>
						<div class="text">
							<h4 class="title"><a href="{$GLOBALS.SITE_BASE}/the-phantom-tollbooth-by-juster-norton/work/24826">The Phantom Tollbooth</a></h4>
							<div class="author">Norton Juster</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="page-width" id="rare-books">
	<h2>From the Rare Book Room</h2>
	<div class="horiz-scroll books">
	    <ul>
			{* LEAVE THESE AS FALL BACK, Jaxie script replaces dynamically and ensures only live content *}
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/080/306/4306080.0.m.jpg" alt="Nineteen Eighty-Four [1984]"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/nineteen-eighty-four-1984-orwell-george/d/4306080">Nineteen Eighty-Four [1984]</a></h4>
	                <div class="author">Orwell, George [Eric Arthur Blair]</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/704/849/247849704.0.m.1.jpg" alt="Horae in laudem beatissime virginis Marie. Secundum consuetudinem ecclesiae parisiensis"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/horae-laudem-beatissime-virginis-marie-secundum/d/247849704">Horae in laudem beatissime virginis Marie. Secundum consuetudinem ecclesiae parisiensis</a></h4>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/156/001/512001156.0.m.1.jpg" alt="The History of the World, in Five Books"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/history-world-five-books-sir-walter/d/512001156">The History of the World, in Five Books</a></h4>
	                <div class="author">Sir Walter Ralegh, Kt</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/533/357/536357533.0.m.2.jpg" alt="Metropolis (Original UK Program for the 1927 film)"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/metropolis-original-uk-program-1927-film/d/536357533">Metropolis (Original UK Program for the 1927 film)</a></h4>
	                <div class="author">Lang, Fritz (director); Thea von Harbou (screenplay, novel)</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/053/843/563843053.0.m.jpg" alt="Naturgeschichte des Pflanzenreichs nach dem Linnéschen System. 54 fein koloriete Doppelfoliotafeln mit über 650 naturgetreuen Abbildungen und 93 seiten erläuterndem Text. Nach G.H. v. Schuberts Lehrbuch der Naturgeschichte neu bearbeitet von Moritz Willkomm... Mit einer Vorrede von Gotthilf Heinrich v. Schubert. Vierte vermehrte Auflage"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/naturgeschichte-pflanzenreichs-linneschen-system-54-fein/d/563843053">Naturgeschichte des Pflanzenreichs nach dem Linnéschen System. 54 fein koloriete Doppelfoliotafeln mit über 650 naturgetreuen Abbildungen und 93 seiten erläuterndem Text. Nach G.H. v. Schuberts Lehrbuch der Naturgeschichte neu bearbeitet von Moritz Willkomm... Mit einer Vorrede von Gotthilf Heinrich v. Schubert. Vierte vermehrte Auflage</a></h4>
	                <div class="author">WILLKOMM, (Heinrich) Moritz (1822-1895)</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/286/683/316683286.0.m.1.jpg" alt="Where the Wild Things Are"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/where-wild-things-sendak-maurice/d/316683286">Where the Wild Things Are</a></h4>
	                <div class="author">by SENDAK, Maurice</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/777/134/626134777.0.m.jpg" alt="[Idyllia] [and other texts]"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/idyllia-other-texts-theocritus-hesiod/d/626134777">[Idyllia] [and other texts]</a></h4>
	                <div class="author">Theocritus; Hesiod</div>
	            </div>
	        </li>
	        <li>
	            <div class="image"><img src="https://d3525k1ryd2155.cloudfront.net/h/155/892/742892155.0.m.jpg" alt="Betty Crocker's Picture Cook Book / Cookbook : Hardcover - First Edition"></div>
	            <div class="text">
	                <h4 class="title"><a href="{$GLOBALS.SITE_BASE}/book/betty-crockers-picture-cook-book-cookbook/d/742892155">Betty Crocker's Picture Cook Book / Cookbook : Hardcover - First Edition</a></h4>
	                <div class="author">Betty Crocker Kitchens</div>
	            </div>
	        </li>
	    </ul>
	</div>
	<div class="btn wide"><a href="/rare-books.html">View All</a></div>
</section>

<section id="blog" class="page-width two-columns image-left">
	<div class="item">
		<div class="image">
			<a href="{$GLOBALS.SITE_BASE}/book-collecting/what-to-collect/collecting-signed-books/"><img src="https://d3525k1ryd2155.cloudfront.net/web/potter.jpg" width="275" height="289" alt="Harry Potter and the Philosopher's Stone, Signed by J.K. Rowling"></a>
		</div>
		<div class="text">
			<h3>Collecting Signed Books</h3>
			<p>Editions signed or inscribed by the author are among the most desirable copies of a book for most collectors, but collecting signed books can sometimes require some acumen.</p>
			<ul>
				<li><a href="{$GLOBALS.SITE_BASE}/signed-books">More on collecting signed books</a></li>
				<li><a href="{$GLOBALS.SITE_BASE}/book-collecting/">More articles on book collecting</a></li>
			</ul>
			<div class="more"><a href="{$GLOBALS.SITE_BASE}/book-collecting/what-to-collect/collecting-signed-books/">Learn More</a></div>
		</div>	
	</div>
	<div class="item">
		<div class="image">
			<a href="{$GLOBALS.SITE_BASE}/first-editions"><img src="https://d3525k1ryd2155.cloudfront.net/h/986/377/1255377986.0.m.jpg" width="200" height="273" alt="First Edition of The Sound and the Fury"></a>
		</div>
		<div class="text">
			<h3>Collecting First Edition Books</h3>
			<p>What is a first edition and what makes them collectible? How do you know when you're looking at a first edition? All of these questions and more are answered in our book collecting guide.</p>
			<ul>
				<li><a href="{$GLOBALS.SITE_BASE}/first-edition-identification/">First edition identification guide</a></li>
				<li><a href="{$GLOBALS.SITE_BASE}/book-collecting/basics/brief-introduction/">Learn about book collecting</a></li>
				<li><a href="{$GLOBALS.SITE_BASE}/book_collecting_terminology/">Book terminology</a></li>
			</ul>
			<div class="more"><a href="{$GLOBALS.SITE_BASE}/first-editions">Learn More</a></div>
		</div>	
	</div>
</section>

<section class="page-width" id="pledge-icons">
	<h2>Shop with Confidence</h2>
	<div class="three-columns">
		{getTemplate file='partials/box-in-stock-guarantee.tpl'}
		{getTemplate file='partials/box-return-guarantee.tpl'}
		{getTemplate file='partials/box-fiercly-indie.tpl'}
	</div>
</section>	


<div id="meta-holder">
	<div id="meta-holder-heading"></div>
	<div id="meta-holder-content"></div>
</div>