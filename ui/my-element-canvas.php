<p>Here you can add your own UI stuff</p>

<p>Form fields should be name="config[group_slug][field_slug]"</p>
<p>Values can then be accessed by <code>$element['group_slug']['field_slug']</code> in the <code>frontier_render_element-{element_type}</code> filter.</p>
<label>Try add a value here to test</label>
<input name="config[group_slug][field_slug]" type="text" value="<?php if(!empty($element['group_slug']['field_slug'])){ echo $element['group_slug']['field_slug']; } ?>">