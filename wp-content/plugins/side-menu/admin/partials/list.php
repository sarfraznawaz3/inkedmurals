<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<table>
    <thead>
		<tr>
			<th><u>Order</u></th>
			<th><u>Menu Item</u></th>
			<th><u>Type</u></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
    <tbody>
		<?php
            if ($resultat) {
				$i = 0;	  
				foreach ($resultat as $key => $value) {
					$i++;
					if($i>5) break;
					$id = $value->id;                
					$order = $value->menu_order;
					$title = $value->title;
					$menu_type = $value->menu_type;
				?>
				<tr>
					<td><?php echo $order; ?></td>
					<td><?php echo $title; ?></td>
					<td><?php echo $menu_type; ?></td>
					<td><u><a href="admin.php?page=<?php echo $this->pluginname;?>&tool=add&act=update&id=<?php echo $id; ?>"><?php esc_attr_e("Edit", "wow-marketings") ?></a></u></td>
					<td><u><a href="admin.php?page=<?php echo $this->pluginname;?>&info=del&did=<?php echo $id; ?>"><?php esc_attr_e("Delete", "wow-marketings") ?></a></u></td>
				</tr>
				<?php
				}        } 
		?>
	</tbody>
</table>