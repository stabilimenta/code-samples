{assign var='page_id' value='search-results'}
{assign var='preview_void_links' value=true}
{assign var='search_criteria_str' value=''}

{foreach from=$SEARCHED_FOR item=CRITERIA}
	{capture assign='search_criteria_str'}{$search_criteria_str}{if $search_criteria_str != ''};{/if} {$CRITERIA.label}: {$CRITERIA.value}{/capture}
{/foreach}

{assign var='title' value='Search Results for '|strcat:$search_criteria_str|strcat:' | '|strcat:$GLOBALS.SITE_NAME}
{assign var='base_url' value='https://'|strcat:$smarty.server.HTTP_HOST}

{capture assign="head"}Search Results{/capture}
{if $PARAM.days_back == 1}
	{if $PARAM.rare_books}
		{capture assign="subhead"}Just arrived - Rare books{/capture}
	{else}
		{capture assign="subhead"}Just arrived - Used, new &amp; out-of-print books{/capture}
	{/if}
{elseif $PARAM.catalog_name}
	{capture assign="subhead"}{$PARAM.catalog_name}{/capture}
{elseif $PARAM.biblio_id}
	{capture assign="subhead"}
		<span class="title">{$PARAM.title|substr:'0':'48'}</span>{if $PARAM.title|@strlen > 48}&hellip;{/if}
		{if $PARAM.author}
			by <span class="author">{$PARAM.author|ucwords|substr:'0':'32'}</span>{if $PARAM.author|@strlen > 32}&hellip;{/if}
		{/if}
	{/capture}
{elseif $PARAM.author != ""}
	{if $PARAM.rare_books}
		{capture assign="subhead"}Rare Books by <span class="author">{$PARAM.author}</span>{/capture}
	{elseif $PARAM.title != ''}
		{capture assign="subhead"}<span class="title">{$PARAM.title|stripslashes}</span> by <span class="author">{$PARAM.author}</span>{/capture}
	{elseif $PARAM.signed && $PARAM.first}
		{capture assign="subhead"}Signed first editions by <span class="author">{$PARAM.author}</span>{/capture}
	{elseif $PARAM.signed}
		{capture assign="subhead"}Signed books by <span class="author">{$PARAM.author}</span>{/capture}
	{elseif $PARAM.first}
		{capture assign="subhead"}First editions by <span class="author">{$PARAM.author}</span>{/capture}
	{else}
		{capture assign="subhead"}Books by <span class="author">{$PARAM.author|stripslashes}</span>{/capture}
	{/if}
{elseif $PARAM.title != ''}
	{if $PARAM.signed && $PARAM.first}
		{capture assign="subhead"}Signed first editions of <span class="title">{$PARAM.title|stripslashes}</span>{/capture}
	{elseif $PARAM.signed}
		{capture assign="subhead"}Signed copies of <span class="title">{$PARAM.title|stripslashes}</span>{/capture}
	{elseif $PARAM.first}
		{capture assign="subhead"}First editions of <span class="title">{$PARAM.title|stripslashes}</span>{/capture}
	{elseif $PARAM.publisher}
		{capture assign="subhead"}<span class="title">{$PARAM.title|stripslashes}</span> published by {$PARAM.publisher|stripslashes}{/capture}
	{else}
		{capture assign="subhead"}<span class="title">{$PARAM.title|stripslashes}</span>{/capture}
	{/if}
{elseif $PARAM.keywords}
	{if $PARAM.signed && $PARAM.first}
		{capture assign="subhead"}Signed first editions matching <span class="keywords">{$PARAM.keywords|stripslashes}</span>{/capture}
	{elseif $PARAM.signed}
		{capture assign="subhead"}Signed copies matching <span class="keywords">{$PARAM.keywords|stripslashes}</span>{/capture}
	{elseif $PARAM.first}
		{capture assign="subhead"}First editions matching <span class="keywords">{$PARAM.keywords|stripslashes}</span>{/capture}
	{else}
		{capture assign="subhead"}<span class="keywords">{$PARAM.keywords|stripslashes}</span>{/capture}
	{/if}
{elseif $PARAM.subject_name != ''}
	{if $PARAM.maxprice && $PARAM.maxprice <= 2}
		{capture assign="subhead"}Bargain &amp; Cheap {$PARAM.subject_name} Books{/capture}
	{else}
		{capture assign="subhead"}{$PARAM.subject_name} Books {$PARAM.subject_old_name}{/capture}
	{/if}
{elseif $PARAM.dbrowse_author != ''}
	{capture assign="subhead"}Authors starting with {$PARAM.letter|strtoupper} from {$PARAM.dealer_name}{/capture}
{elseif $PARAM.dbrowse_title != ''}
	{capture assign="subhead"}Titles starting with {$PARAM.letter|strtoupper} from {$PARAM.dealer_name}{/capture}
{elseif $PARAM.dealer_id != ''}
	{assign var="ALT" value=$SEARCH.class}
	{capture assign="subhead"}
		Books from {$PARAM.dealer_name} {$ALT->SetDealer()}
	{/capture}
	{capture assign="subhead_link"}
		<a href="{$GLOBALS.SITE_BASE}/search.php{$ALT->GetSearchString()}">Search All Bookstores Instead</a>
	{/capture}
{elseif $PARAM.sales != ''}
	{capture assign="subhead"}Discount Books on Sale{/capture}
{elseif $PARAM.sale_info}
	{capture assign="subhead"}{$PARAM.sale_info.name} from {$PARAM.sale_info.business_name}{/capture}
{elseif $PARAM.isbn && $RESULTS[0].title}
	{if !$EDITION}
		{if $RESULTS[0].author}
			{capture assign="subhead"}<span class="title">{$RESULTS[0].title}</span> by <span class="author">{$RESULTS[0].author}</span> (ISBN: {$PARAM.isbn}){/capture}
		{else}
			{capture assign="subhead"}<span class="title">{$RESULTS[0].title}</span>{/capture}
		{/if}
	{/if}
{/if}

{if $PARAM.category_name}
	{capture assign="subhead"}{$PARAM.category_name}{/capture}
	{capture assign="title"}{$PARAM.category_name}{if $PARAM.page} - Page {$PARAM.page}{/if} - Used &amp; Antiquarian Books{/capture}
	{capture assign="description"}{assign var="last" value=$RESULTS|@sizeof}{$RESULTS.0.title}{math equation=x-y x=$last y=1 assign="z"}{if $z > 0} to {$RESULTS.$z.title}{/if}{/capture}
{/if}

<div id="page-content" class="page-width">
    <main id="primary">
		<header id="content-header" class="results-controls">
			<nav aria-label="Breadcrumb" class="breadcrumb">
		        <ol>
		            <li><a href="/">Home</a></li>
		            <li aria-current="page">Search Results</li>
		        </ol>
			</nav>
			<hgroup class="content-heading">
			    <h1>{$head}{if $subhead}: <span class="subhead">{$subhead}</span>{/if}</h1>
			    
			</hgroup>
			<ul class="pagination">
				{if $SEARCH.has_back_link} 
					<li class="previous"><a href="{$SEARCH.back_link}">Back</a></li>
				{/if}
				{if $SEARCH.first_page < $SEARCH.current_page - 3}
					<li><a href="{$SEARCH.first_page_link}" title="Go to the first page of your results">{$SEARCH.first_page}</a></li>
					<li class="empty">...</li>
				{/if}
				{section name=mylink loop=$PAGES_PREVIOUS}
					{strip}
						<li><a href="{$PAGES_PREVIOUS[mylink].link}" title="Go to page {$PAGES_PREVIOUS[mylink].page} of your results">{$PAGES_PREVIOUS[mylink].page|number_format:"0"}</a></li>
					{/strip}
				{/section}
				<li class="current">{$SEARCH.current_page|number_format:"0"}</li>
				{section name=mylink loop=$PAGES_NEXT}
					{strip}
						<li><a href="{$PAGES_NEXT[mylink].link}" title="Go to page {$PAGES_NEXT[mylink].page} of your results">{$PAGES_NEXT[mylink].page|number_format:"0"}</a></li>
					{/strip}
				{/section}
				{if $SEARCH.last_page > $SEARCH.current_page + 3}
					<li class="empty">...</li>
					<li><a href="{$SEARCH.last_page_link}" title="Go to the last page of your results">{$SEARCH.last_page}</a></li>
				{/if}
				{if $SEARCH.has_forward_link} 
					<li class="next"><a href="{$SEARCH.forward_link}">Next</a></li>	
				{/if}
			</ul>
			<div class="results-count">Results {$SEARCH.shown_start} - {$SEARCH.shown_end} of {$SEARCH.matches}</div>
			<div class="items-per-page">
				<label for="per-page-top">Items per page</label>
				<select name="page_size" id="per-page-top" class="jump-menu">
					{if $smarty.server.REQUEST_URI|strstr:'pageper=' }
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=pageper=)\d+/':'12'|regex_replace:'/(?<=page=)\d+/':'1'}"{if $smarty.get.pageper == '12'} selected="selected"{/if}>12</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=pageper=)\d+/':'24'|regex_replace:'/(?<=page=)\d+/':'1'}"{if $smarty.get.pageper == '24'} selected="selected"{/if}>24</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=pageper=)\d+/':'48'|regex_replace:'/(?<=page=)\d+/':'1'}"{if $smarty.get.pageper == '48'} selected="selected"{/if}>48</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=pageper=)\d+/':'96'|regex_replace:'/(?<=page=)\d+/':'1'}"{if $smarty.get.pageper == '96'} selected="selected"{/if}>96</option>
					{else}
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=page=)\d+/':'1'}&pageper=12">12</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=page=)\d+/':'1'}&pageper=24">24</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=page=)\d+/':'1'}&pageper=48">48</option>
						<option value="{$smarty.server.REQUEST_URI|regex_replace:'/(?<=page=)\d+/':'1'}&pageper=96">96</option>
					{/if}
				</select>
			</div>		    
			<div class="search-sort">
				<label for="result-sort">Sort By</label>
				<select id="result-sort" name="sort" class="jump-menu">
					{if $REFINE.sorts.priceasc_link != ''}
						<option value="{$REFINE.sorts.priceasc_link}"{if $PARAM.sort == 'priceasc'} selected="selected"{/if}>Lowest price</option>
					{/if}
					{if $SCHEMA_VERSION == '2.0'}
						{if $VISITOR.country_id == '36' && $REFINE.sorts.priceshipnzasc_link != ''}
							<option value="{$REFINE.sorts.priceshipnzasc_link}"{if $PARAM.sort == 'price_ship_nzasc'} selected="selected"{/if}>
								Lowest total w/ shipping to New Zealand
							</option>
						{/if}
						{if $VISITOR.country_id == '7' && $REFINE.sorts.priceshipauasc_link != ''}
							<option value="{$REFINE.sorts.priceshipauasc_link}"{if $PARAM.sort == 'price_ship_auasc'} selected="selected"{/if}>
								Lowest total w/ shipping to Australia
							</option>
						{/if}
						{if $VISITOR.country_id == '2' && $REFINE.sorts.priceshipukasc_link != ''}
							<option value="{$REFINE.sorts.priceshipukasc_link}"{if $PARAM.sort == 'price_ship_ukasc'} selected="selected"{/if}>
								Lowest total w/ shipping to UK
							</option>
						{/if}
						{if $VISITOR.country_id == '1' && $REFINE.sorts.priceshipusasc_link != ''}
							<option value="{$REFINE.sorts.priceshipusasc_link}"{if $PARAM.sort == 'price_ship_usasc'} selected="selected"{/if}>
								Lowest total w/ shipping to U.S.
							</option>
						{/if}
						{if $VISITOR.country_id == '6' && $REFINE.sorts.priceshipdeasc_link != ''}
							<option value="{$REFINE.sorts.priceshipdeasc_link}"{if $PARAM.sort == 'price_ship_deasc'} selected="selected"{/if}>
								Lowest total w/ shipping to Germany
							</option>
						{/if}
						{if $VISITOR.country_id == '23' && $REFINE.sorts.priceshipjpasc_link != ''}
							<option value="{$REFINE.sorts.priceshipjpasc_link}"{if $PARAM.sort == 'price_ship_jpasc'} selected="selected"{/if}>
								Lowest total w/ shipping to Japan
							</option>
						{/if}
						{if $VISITOR.country_id == '5' && $REFINE.sorts.priceshipcaasc_link != ''}
							<option value="{$REFINE.sorts.priceshipcaasc_link}"{if $PARAM.sort == 'price_ship_caasc'} selected="selected"{/if}>
								Lowest total w/ shipping to Canada
							</option>
						{/if}
					{/if}
					{if $REFINE.sorts.pricedesc_link != ''}
						<option value="{$REFINE.sorts.pricedesc_link}"{if $PARAM.sort == 'pricedesc'} selected="selected"{/if}>Highest price</option>
					{/if}
					{if $REFINE.sorts.authorasc_link != ''}
						<option value="{$REFINE.sorts.authorasc_link}"{if $PARAM.sort == 'authorasc'} selected="selected"{/if}>Author A-Z</option>
					{/if}
					{if $REFINE.sorts.authordesc_link != ''}
						<option value="{$REFINE.sorts.authordesc_link}"{if $PARAM.sort == 'authordesc'} selected="selected"{/if}>Author Z-A</option>
					{/if}
					{if $REFINE.sorts.titleasc_link != ''}
						<option value="{$REFINE.sorts.titleasc_link}"{if $PARAM.sort == 'titleasc'} selected="selected"{/if}>Title A-Z</option>
					{/if}
					{if $REFINE.sorts.titledesc_link != ''}
						<option value="{$REFINE.sorts.titledesc_link}"{if $PARAM.sort == 'titledesc'} selected="selected"{/if}>Title Z-A</option>
					{/if}
					{if $REFINE.sorts.iddesc_link != ''}
						<option value="{$REFINE.sorts.iddesc_link}"{if $PARAM.sort == 'iddesc'} selected="selected"{/if}>Most recently added</option>
					{/if}
					{if $REFINE.sorts.idasc_link != ''}
						<option value="{$REFINE.sorts.idasc_link}&alt_layout=tile"{if $PARAM.sort == 'idasc'} selected="selected"{/if}>Oldest</option>
					{/if}
					{if $REFINE.sorts.relevance_link != ''}
						<option value="{$REFINE.sorts.relevance_link}"{if $PARAM.sort == 'relevance'} selected="selected"{/if}>Best match</option>
					{/if}
				</select>
			</div>
		</header>
		<div id="primary-content">
			<div class="search-results-list">
				<div class="search-criteria">
					<ul class="btn-list">
						{foreach from=$SEARCHED_FOR item=CRITERIA}
							{if $CRITERIA.label|strpos:"product types" !== false}
								{if $CRITERIA.value|strpos:"Autograph" !== false}
									{assignel var=CRITERIA key=value value="Autographs"}
								{elseif $CRITERIA.value|strpos:"Manuscript" !== false}
									{assignel var=CRITERIA key=value value="Manuscripts"}
								{elseif $CRITERIA.value|strpos:"Map" !== false}
									{assignel var=CRITERIA key=value value="Maps"}	
								{elseif $CRITERIA.value|strpos:"Book" !== false}
									{assignel var=CRITERIA key=value value="Books"}		
								{elseif $CRITERIA.value|strpos:"Original art" !== false}
									{assignel var=CRITERIA key=value value="Original Art & Illustration"}
								{elseif $CRITERIA.value|strpos:"Pamphlet" !== false}
									{assignel var=CRITERIA key=value value="Periodicals & Pamphlets"}
								{elseif $CRITERIA.value|strpos:"Photographic" !== false}
									{assignel var=CRITERIA key=value value="Photographs"}	
								{elseif $CRITERIA.value|strpos:"Poster" !== false}
									{assignel var=CRITERIA key=value value="Prints & Posters"}		
								{else}
									{assign var=someVar value=" "|explode:$CRITERIA.value}
									{assignel var=CRITERIA key=value value=$someVar[0]}
								{/if}
							{/if}
						
							<li class="smaller"><a href="{$CRITERIA.link}" data-placement="top" data-toggle="tooltip" title="Click to remove this from your search criteria." rel="nofollow" aria-label="Click to remove this from your search criteria."><span class="label">{$CRITERIA.label}:</span> {$CRITERIA.value}</a></li>
						{/foreach}
					</ul>
					<button id="filters-trigger" class="d-md-none btn">Filter Results</button>
					<div id="style-selector">
						<button id="list" data-style="type-2" aria-label="Click to display search results as a list" title="Display search results as a list.">
							List
						</button>
						<button id="grid" data-style="type-1" aria-label="Click to display search results as a grid" title="Display search results as a grid.">
							Grid
						</button>
					</div>
				</div><!-- /.search-criteria -->
				<div id="results-list" class="summary type-1 row">
					{assign var='SORTCOUNT' value=$SEARCH.class->start}
					{assign var='SORTCOUNT' value=$SORTCOUNT+1}
					{section name=c loop=$RESULTS}
						{assign var="RESULT" value=$RESULTS[c]}
						<div class="item <% Book_call_to_action {$RESULT.book_id} %>">
							<a href="/item/{$RESULT.book_id}" class="box-link">
								{* 	<pre>{$RESULT|@print_r}</pre> *}
								<div class="image-wrap">
									{fetchAllBookImages itemprop='false' book_id=$RESULT.book_id size='l' clouded='true' skip_checks='false' alt=$RESULT.title|truncate:40 class='' assign="bookphoto"}
									{ if $bookphoto.meta.numFound gt 0 }
										{* 	
											Biblio processes uploaded images of books into 4 sizes, 
											going to iterate over these in a loop to find what we need... 
										*}
										{assign var='img_sizes' value=','|explode:"l,m,b,s"}
										{foreach from=$img_sizes item='img_size'}
											{if $bookphoto.results[0].$img_size.path}
												<img src="{$bookphoto.results[0].$img_size.path}" alt="{$BOOK.title|truncate:40}" width="{$bookphoto.results[0].$img_size.width}" height="{$bookphoto.results[0].$img_size.height}"> 
												{* 
													because of caching, we don't know a book's availability status
													until a last-stage look up which will hide or show these 
													elements with a css class
												*}
												<div class="sold-banner">Sold</div>
												<div class="reserved-banner">Reserved</div>
												<div class="corner-banners">
													{*if $RESULT.book_id gt $GLOBALS.last_id_b4_open*}
													{if $RESULT.restocking == true}
														<div class="new-item">New Item</div>
													{/if}
													{if $RESULT.price|replace:',':''|number_format:0:'':'' lte 100}
														<div class="discovery-item"><span class="currency-symbol">$</span>100 or Less</div>
													{/if}
													{* this is looking for the categories that the ABA and ABAA benevolent fund might be held in *}
													{if $RESULT.vbf_category_1|strstr:'10225' || 
														$RESULT.vbf_category_1|strstr:'10226' ||
														$RESULT.vbf_category_2|strstr:'10225' || 
														$RESULT.vbf_category_2|strstr:'10226' ||
														$RESULT.vbf_category_3|strstr:'10225' || 
														$RESULT.vbf_category_3|strstr:'10226' 
													}
														<div class="benev-fund-item">Benevolent Fund</div>
													{/if} 
												</div>
											    {break}
										    {/if}
										{/foreach}
									{else}
										<div class="no-photo-wrap">
											<div class="sold-banner">Sold</div>
											<div class="reserved-banner">Reserved</div>
											<div class="corner-banners">
												{*if $RESULT.book_id gt $GLOBALS.last_id_b4_open*}
												{if $RESULT.restocking == true}
													<div class="new-item">New Item</div>
												{/if}
												{if $RESULT.price|replace:',':''|number_format:0:'':'' lte 100}
													<div class="discovery-item"><span class="currency-symbol">$</span>100 or Less</div>
												{/if}
												{* this is looking for the categories that the ABA and ABAA benevolent fund might be held in *}
												{if $RESULT.vbf_category_1|strstr:'10225' || 
													$RESULT.vbf_category_1|strstr:'10226' ||
													$RESULT.vbf_category_2|strstr:'10225' || 
													$RESULT.vbf_category_2|strstr:'10226' ||
													$RESULT.vbf_category_3|strstr:'10225' || 
													$RESULT.vbf_category_3|strstr:'10226' 
												}
													<div class="benev-fund-item">Benevolent Fund</div>
												{/if} 
											</div>
											<div class="no-photo">No Photo Available</div>
										</div>
									{/if}
								</div>
								<div class="text-wrap">
									<h3 class="title">{$RESULT.title}{if $RESULT.subtitle}{if $RESULT.title|substr:-1 != ':'}:{/if} <span class="subtitle">{$RESULT.subtitle}</span>{/if}</h3>
									<div class="author">{$RESULT.author}</div>
									<div class="description">{$RESULT.full_description|strip_tags:false|truncate:300:"&hellip;"|nl2br}</div>
									<div class="offered-by">Offered by {$RESULT.business_name}</div>		
									<div class="price">
										{* <span class="currency-symbol">{$RESULT.currency_symbol}</span>{$RESULT.price|replace:',':''|number_format} *}
										<span class="currency-symbol">{$RESULT.currency_symbol}</span>{$RESULT.price|replace:',':''|number_format}
										{if $RESULT.discount}
											<span class="discounted">({$RESULT.discount}% dealer discount applied.)</span>
										{/if}
									</div>
									<div class="more">View Details</div>
									{if $RESULT.keyfacts|@sizeof}
										<div class="term-wrapper">
											<ul class="terms">
												{foreach from=$RESULT.keyfacts item=keyfact}
												    <li>{$keyfact|truncate:35:"&hellip;"}</li>
												{/foreach}
											</ul>
										</div>
									{/if}
								</div>
							</a>
							<div class="price-box">
								<div class="price">
									{* <span class="currency-symbol">{$RESULT.currency_symbol}</span>{$RESULT.price|replace:',':''|number_format} *}
									<span class="currency-symbol">{$RESULT.currency_symbol}</span>{$RESULT.price|replace:',':''|number_format}
									{if $RESULT.discount}
										<span class="discounted">({$RESULT.discount}% dealer discount applied.)</span>
									{/if}
								</div>
								<div class="sold-banner">Sold</div>
								<div class="reserved-banner">Reserved</div>
								<div class="buy">
									{getAddtoCartButton book=$RESULT suppress_buy_now=1 include_price=0 INT_SRC='vbf' override_country=$OVERRIDE_COUNTRY override_speed=$OVERRIDE_SPEED price_container_extra='' price_container_wrapper='' price_class='' button_class='btn' button_extra='' form_class='' primary_button_class='primary' secondary_button_class='secondary' style='en20' use_ext_cart='1'}
								</div>
							</div>
						</div>
					{/section}
				</div>
				<ul class="pagination">
					{if $SEARCH.has_back_link} 
						<li class="previous"><a href="{$SEARCH.back_link}">Back</a></li>
					{/if}
					{if $SEARCH.first_page < $SEARCH.current_page - 3}
						<li><a href="{$SEARCH.first_page_link}" title="Go to the first page of your results">{$SEARCH.first_page}</a></li>
						<li class="empty">...</li>
					{/if}
					{section name=mylink loop=$PAGES_PREVIOUS}
						{strip}
							<li><a href="{$PAGES_PREVIOUS[mylink].link}" title="Go to page {$PAGES_PREVIOUS[mylink].page} of your results">{$PAGES_PREVIOUS[mylink].page|number_format:"0"}</a></li>
						{/strip}
					{/section}
					<li class="current">{$SEARCH.current_page|number_format:"0"}</li>
					{section name=mylink loop=$PAGES_NEXT}
						{strip}
							<li><a href="{$PAGES_NEXT[mylink].link}" title="Go to page {$PAGES_NEXT[mylink].page} of your results">{$PAGES_NEXT[mylink].page|number_format:"0"}</a></li>
						{/strip}
					{/section}
					{if $SEARCH.last_page > $SEARCH.current_page + 3}
						<li class="empty">...</li>
						<li><a href="{$SEARCH.last_page_link}" title="Go to the last page of your results">{$SEARCH.last_page}</a></li>
					{/if}
					{if $SEARCH.has_forward_link} 
						<li class="next"><a href="{$SEARCH.forward_link}">Next</a></li>	
					{/if}
				</ul>
			</div>
			<div class="search-sidebar" id="search-filters">
				{getTemplate file="sidebar_search.tpl"}
			</div>
		</div>
    </main>
</div>

{if $SEARCH_ALERT}
	<div class="wrapper">
		<div class="alert alert-{$SEARCH_ALERT.class} alert-dismissible text-center" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4>{$SEARCH_ALERT.title} {$SEARCH_ALERT.message}</h4>
			{if $SEARCH_ALERT.actions|is_array}
				<div class="row-fluid">
					{section name=s loop=$SEARCH_ALERT.actions}
						<a href="{$SEARCH_ALERT.actions[s].url}" class="{$SEARCH_ALERT.actions[s].class} smaller">{$SEARCH_ALERT.actions[s].text}</a>
					{/section}
				</div>
			{/if}
		</div>
	</div>
{/if}

{if $SEARCH.first_page < $SEARCH.current_page}
	{assign var="show" value="0"}
{/if}

{if $show}
	<div id="suggest-nav">
		<p class="suggest-intro">Your search returned <span class="intro-num">{$SEARCH.matches}<span> books.  We have some suggestions to narrow your selection:</p>
		{$suggest}
		<span id="more-search-link"></span>
	</div>
{/if}

<div id="remote-content-modal" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h2 id="modal-title" class="modal-title"></h2>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="loading"><div class="spinner"></div><h4>Hang on&hellip; we're fetching the requested page.</h4></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-small" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
