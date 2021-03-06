<?php

class FlexiUserRoleTableList
{
    // property declaration
    private $user_roles;
    private $table_id;
    private $step_num;
    private $all_roles;
    private $editable_roles;
    
    // method declaration
    public function __construct($step_num, $table_id) {
      $this->step_num = $step_num;
      $this->table_id = $table_id;
      global $wp_roles;

      $this->all_roles = $wp_roles->roles;
      $this->editable_roles = apply_filters('editable_roles', $this->all_roles);

    }
    
    public function BuildTable()
    {
      ?>

      <fieldset class="closify-tinymce-fieldset">
        <legend><strong>(Step <?php echo $this->step_num; ?>)</strong>:Select by user:</legend>
        <div> 
          
          <div>  
            <h4>Select multiple Users as a source of images</h4>
          </div>

          <table id="<?php echo $this->table_id; ?>" class="display compact closify-tinymce-table" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="all_roles" name="all_roles" value="selected" >All</th>
                <th>Role name</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Role name</th>
              </tr>
            </tfoot>
            <tbody id="the-list" >
            <?php
              foreach($this->editable_roles as $key=>$role)
              {
            ?>
                  <tr>
                    <td><?php echo '<input type="checkbox" class="roles" name="roles[]" value="'.$role["name"].'" />' ?></td>
                    <td><?php echo $role["name"]; ?></td>
                  </tr>
              <!-- LOOP: Usual Post Template Stuff Here-->

            <?php } ?>
                </tbody>
              </table>
            </div>
          </fieldset>

      <?php 
    }
    
}

?>