<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">

<tr>
	<th><label for="encyclopedia_type"><?php echo I18n::t('Encyclopedia type') ?></label></th>
	<td>
		<input type="text" name="encyclopedia_type" id="encyclopedia_type" value="<?php echo esc_Attr(Options::get('encyclopedia_type')) ?>">
		<p class="help">
			<?php echo I18n::t('This is how your encyclopedia is called in the dashboard. For example: Encyclopedia, Lexicon, Glossary, Knowledge Base, etc.') ?>
			<?php #echo I18n::t('You can change this at any time later without worries.') ?>
		</p>
	</td>
</tr>

<tr>
	<th><label for="item_singular_name"><?php echo I18n::t('Item singular name') ?></label></th>
	<td>
		<input type="text" name="item_singular_name" id="item_singular_name" value="<?php echo esc_Attr(Options::get('item_singular_name')) ?>">
		<p class="help"><?php echo I18n::t('The singular name for an encyclopedia item. For example: Entry, Term, Article, etc.') ?></p>
	</td>
</tr>

<tr>
	<th><label for="item_plural_name"><?php echo I18n::t('Item plural name') ?></label></th>
	<td>
		<input type="text" name="item_plural_name" id="item_plural_name" value="<?php echo esc_Attr(Options::get('item_plural_name')) ?>">
		<p class="help"><?php echo I18n::t('The plural name for multiple encyclopedia items. For example: Entries, Terms, Articles, etc.') ?></p>
	</td>
</tr>


<?php if (get_Option('permalink_structure')): ?>
	<tr>
		<th><label for="archive_url_slug"><?php echo I18n::t('Archive URL slug') ?></label></th>
		<td>
			<div class="input-row">
				<div><?php echo Home_Url('/') ?></div>
				<div class="input-element"><input type="text" name="archive_url_slug" id="archive_url_slug" value="<?php echo esc_Attr(Options::get('archive_url_slug')) ?>"></div>
			</div>
			<p class="help"><?php echo I18n::t('The url slug of your encyclopedia archive. This slug must not used by another post type or page.') ?></p>
		</td>
	</tr>

	<tr>
		<th><label for="item_url_slug"><?php echo I18n::t('Item URL slug') ?></label></th>
		<td>
			<div class="input-row">
				<div><?php echo Home_Url('/') ?></div>
				<div class="input-element"><input type="text" name="item_url_slug" id="item_url_slug" value="<?php echo esc_Attr(WPML::isPostTypeSlugTranslationEnabled() ? Options::get('archive_url_slug') : Options::get('item_url_slug')) ?>" <?php disabled(WPML::isPostTypeSlugTranslationEnabled()) ?> ></div>
				<div><?php echo User_TrailingSlashIt(sprintf(I18n::t('/%%%s-name%%'), sanitize_Title(Post_Type_Labels::getItemSingularName())), 'single') ?></div>
			</div>

			<?php if (WPML::isPostTypeSlugTranslationEnabled()): ?>
				<p class="help warning"><?php echo I18n::t('This option is not available if you translate the post type url slug with WPML.') ?></p>
			<?php else: ?>
				<p class="help">
					<?php echo I18n::t('The url slug of your encyclopedia items.') ?>
					<?php if ($taxonomies = Post_Type::getAssociatedTaxonomies()): $taxonomies = Array_Map(function($taxonomy){ return "%{$taxonomy->name}%"; }, $taxonomies) ?>
						<?php printf(I18n::t('You can use these placeholders: %s'), join(', ', $taxonomies)) ?>
					<?php endif ?>
				</p>
			<?php endif ?>
		</td>
	</tr>
<?php endif ?>

</table>
