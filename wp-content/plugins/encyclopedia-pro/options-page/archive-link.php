<?php Namespace WordPress\Plugin\Encyclopedia ?>

<p>
  <?php printf(I18n::t('The archive url is: <a href="%1$s" target="_blank">%1$s</a>'), get_Post_Type_Archive_Link(Post_Type::post_type_name)) ?>
</p>

<p>
  <?php printf(I18n::t('The RSS feed url is: <a href="%1$s" target="_blank">%1$s</a>'), get_Post_Type_Archive_Feed_Link(Post_Type::post_type_name)) ?>
</p>
