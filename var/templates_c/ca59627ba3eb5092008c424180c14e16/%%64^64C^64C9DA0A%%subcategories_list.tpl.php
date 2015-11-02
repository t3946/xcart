<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/subcategories_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/subcategories_list.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/subcategories_list.tpl"), $this); endif; ?><div class="content-primary">
  <ul data-role="listview" data-type="categories-list">
    <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subcategories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subcategories']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['subcat']):
        $this->_foreach['subcategories']['iteration']++;
 if ($this->_tpl_vars['subcat']['order_by'] >= 0 && $this->_tpl_vars['subcat']['order_by'] <= 500 && $this->_tpl_vars['subcat']['product_count'] > 0): ?>
      <li>
        <a href="home.php?cat=<?php echo $this->_tpl_vars['subcat']['categoryid']; ?>
" >
          <?php echo $this->_tpl_vars['subcat']['category']; ?>

        </a>
      </li>
<?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/subcategories_list.tpl"), $this); endif; ?>