<?php 
   $post_type = $session['post_type']; 
   $taxonomies = get_object_taxonomies($post_type,'objects'); 
   if (!empty($taxonomies)) : 
?>
<div class="wpsi-portlet" id="sec_tax_cat_tag">
   <div class="wpsi-portlet-title">
      <div class="caption">
         <span class="caption-subject">
         <?php esc_attr_e( 'Taxonomies , Category , Tags :', 'wp-smart-import' ); ?>
         </span>
      </div>
   </div>
   <div class="wpsi-portlet-body">
      <div class="wpsi-portlet-single-block">
         <div class="wpsi-single-block-content">
            <?php 
            $exclude = array('post_format', 'product_type', 'product_shipping_class');
            foreach ($taxonomies as $key => $value) :
               if (in_array($key, $exclude)) {
                  continue;
               }
               if ("" == $value->labels->name || strpos($value->name, "pa_") === 0 && $post_type == "product") {
                  continue;
               }

            if(!empty($value->public)) : ?>
            <div class="wpsi-taxonomies-container">
               <label>
               <input type="checkbox" name="taxo[<?php echo esc_attr($value->name); ?>]" value="yes" class="show_hide" <?php if (isset($post_data['taxo'][$value->name]) && $post_data['taxo'][$value->name] == "yes") echo "checked"; ?>>
               <?php echo esc_html($value->label); ?>
               </label>
               <div class="slidingDiv wpsi-inner-block">
                  <div class="inner-content">
                     <label>
                        <input type="checkbox" class="show_hide" checked="" >
                        <?php esc_attr_e( 'List of ', 'wp-smart-import' ); echo esc_attr( $value->label ); ?>
                     </label>
                     <div class="slidingDiv inner-content">
                        <textarea name="term[<?php echo esc_attr( $value->name.'_list' ); ?>]" class="wpsi-form-control drop-target wpsi-cat-list input-grp" placeholder="Enter or click of links"><?php if (isset($post_data['term'][$value->name.'_list'])) echo esc_html($post_data['term'][$value->name.'_list']); ?></textarea>

                        <?php $del = isset( $post_data['term'][$value->name.'_delemiter'] ) && !empty( $post_data['term'][$value->name.'_delemiter'] ) ?  $post_data['term'][$value->name.'_delemiter'] : ','; ?>
                        <input type="text" name="term[<?php echo esc_attr( $value->name) .'_delemiter'; ?>]" class="wpsi-form-control short" value="<?php echo esc_attr($del); ?>">

                        <span class="wpsi-note"> 
                           <?php esc_attr_e("NOTE : Seperate with", 'wp-smart-import' ); ?>  
                           <b class="imp-text"><?php esc_attr_e("( , )", 'wp-smart-import' ); ?></b>
                           <?php echo esc_attr(", If ".$value->labels->singular_name." not in list you can write it automatically create new ". $value->labels->singular_name ); ?>
                        </span><br>
                        <?php $terms = get_terms(array('taxonomy' => $value->name,'hide_empty' => false)); ?>
                        <div class="wpsi-post-cat-listing wpsi_<?php echo esc_attr($value->name); ?>" 
                           <?php if(empty($terms)){echo 'style="display: none;"';}?>>
                           <button class="select_all wpsi-button" type="button"><?php esc_attr_e("All", 'wp-smart-import' ); ?></button>
                           <?php	foreach ($terms as $key => $value1) {
                                 echo "<a href='javascript:void(0)' class='post_tax'>".esc_attr( $value1->name ).'</a>';
                              }
                           ?>
                        </div>
                        <?php  ?>
                     </div>
                     <label>
                        <input type="checkbox" class="show_hide"/>
                        <?php echo esc_attr( "Add New ".$value->label ); ?>
                     </label>
                     <div class="slidingDiv inner-content">
                        <input type="text" name="term[<?php echo esc_attr( $value->name ).'_add'; ?>]" class="wpsi-form-control drop-target wpsi-cat-list" placeholder="Example : table, show, mypro..." <?php if (isset($post_data['term'][$value->name.'_add'])) echo esc_attr($post_data['term'][$value->name.'_add']); ?> />

                        <button class="add_term wpsi-button wpsi-button-block" type="button" data-addIn="<?php echo esc_attr($value->name); ?>"><?php esc_attr_e('Save', 'wp-smart-import' ); ?></button>
                        <span class="wpsi-note">
                           <?php echo esc_attr("NOTE : Add Multiple ".$value->labels->singular_name."with <b class='imp-text'>( , )</b> Seperate"); ?>
                        </span><br/>
                     </div>
                     <?php if ($value->hierarchical) : ?>
                     <label>
                        <input type="checkbox" class="show_hide" name="term[<?php echo esc_attr($value->name.'_hierarchical'); ?>]" value="yes" <?php if (isset($post_data['term'][$value->name.'_hierarchical']) && $post_data['term'][$value->name.'_hierarchical'] == "yes") echo esc_attr("checked"); ?> />
                        <?php esc_attr_e('Posts have hierarchical (parent/child) Categories (i.e. Sports > Golf > Clubs > Putters)', 'wp-smart-import' ); ?>
                     </label>
                     <div class="slidingDiv inner-content">
                        <table class="wpsi-table wpsi-cat-group-tab">
                           <tbody>
                           <?php if (isset($post_data['term'][$value->name.'_grp'])):  
                              $post_data['term'][$value->name.'_grp'] = array_values(array_filter($post_data['term'][$value->name.'_grp']));
                              foreach ($post_data['term'][$value->name.'_grp'] as $idx => $val) : ?>
                                 <tr>
                                    <td>
                                       <input type="text" name="term[<?php echo esc_attr($value->name.'_grp'); ?>][]" class="wpsi-form-control drop-target" placeholder="Example : Sports > Golf > Clubs > Putters" value="<?php echo esc_attr( $val ); ?>">
                                    </td>
                                    <td><a class="dashicons dashicons-trash remove-field"></a></td>
                                 </tr>
                              <?php endforeach; 
                                 else: ?>
                              <tr>
                                 <td>
                                    <input type="text" name="term[<?php echo esc_attr($value->name.'_grp'); ?>][]" class="wpsi-form-control drop-target" placeholder="Example : Sports > Golf > Clubs > Putters" />
                                 </td>
                                 <td> 
                                    <a class="dashicons dashicons-trash remove-field"></a> 
                                 </td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                        </table>
                        <span class="wpsi-note"> 
                           <?php esc_attr_e("NOTE : Seperate with <b class='imp-text'>( > )</b>", 'wp-smart-import' ); ?>
                        </span><br/><br/>
                        <div class="add-new-block">
                           <input type="hidden" value="<?php echo esc_attr('term['.$value->name .'_grp][]'); ?>" class="temp-cat-name" />
                           <button class="add_cat_group wpsi-button wpsi-button-block" type="button"> 
                              <?php esc_attr_e("+ Add Another Hierarchy Group", 'wp-smart-import' ); ?>
                           </button>
                        </div>
                     </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
            <?php	endif; 
               endforeach; ?>
         </div>
      </div>
   </div>
   <!-- wpsi-portlet-body END -->
</div>
<!-- wpsi-portlet END -->
<?php endif; ?>