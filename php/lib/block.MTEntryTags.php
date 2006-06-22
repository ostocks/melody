<?php
function smarty_block_MTEntryTags($args, $content, &$ctx, &$repeat) {
    $localvars = array('_tags', 'Tag', '_tags_counter', 'tag_min_count', 'tag_max_count','all_tag_count');
    if (!isset($content)) {
        $ctx->localize($localvars);
        require_once("MTUtil.php");
        $blog_id = $ctx->stash('blog_id');
        $tags = $ctx->mt->db->fetch_entry_tags(array('blog_id' => $blog_id));

        $min = 0; $max = 0;
        $all_count = 0;
        $tagnames = '';
        foreach ($tags as $tag) {
            $count = $tag['tag_count'];
            if ($count > $max) $max = $count;
            if ($count < $min or $min == 0) $min = $count;
            $all_count += $count;
        }
        $ctx->stash('tag_min_count', $min);
        $ctx->stash('tag_max_count', $max);
        $ctx->stash('all_tag_count', $all_count);

        $entry = $ctx->stash('entry');
        $tags = $ctx->mt->db->fetch_entry_tags(array('entry_id' => $entry['entry_id'], 'blog_id' => $blog_id));
        $ctx->stash('_tags', $tags);
        
        $counter = 0;
    } else {
        $tags = $ctx->stash('_tags');
        $counter = $ctx->stash('_tags_counter');
    }
    if ($counter < count($tags)) {
        $tag = $tags[$counter];
        $ctx->stash('Tag', $tag);
        $ctx->stash('_tags_counter', $counter + 1);
        $repeat = true;
        if (($counter > 0) && isset($args['glue'])) {
            $content = $content . $args['glue'];
        }
    } else {
        $ctx->restore($localvars);
        $repeat = false;
    }
    return $content;
}
?>
