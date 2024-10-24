
<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_ticket"><i class="fa fa-plus"></i> Thêm mới</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
			<thead>
  <tr>
    <th class="text-center">#</th>
    <th>Ngày</th>
    <th>Khách hàng</th>
    <th>Vé người lớn</th>
    <th>Vé trẻ em</th>
    <th>Vé cho</th>
    <th>Trạng thái</th>
    <th>Khuyến mãi</th>
    <!-- Commented out the header for "Người bán" -->
    <!-- <th>Người bán</th> -->
    <th>Hành động</th>
  </tr>
</thead>

<tbody>
  <?php
  $i = 1;
  $qry = $conn->query("SELECT t.*, p.name AS ticket_for, pay.type AS type, dis.name_promo AS name_promo, u.firstname as firstname 
  FROM ticket_list t 
  INNER JOIN pricing p ON t.pricing_id = p.id
  LEFT JOIN payment pay ON t.payment_id = pay.id
  LEFT JOIN promo dis ON t.promo_id = dis.id
  LEFT JOIN users u ON t.user_id = u.id
  ORDER BY UNIX_TIMESTAMP(t.date_created) DESC");

  if (!$qry) {
      echo "Query Error: " . $conn->error;
  } else {
      while ($row = $qry->fetch_assoc()):
  ?>
  <tr>
    <td class="text-center"><?php echo $i++ ?></td>
    <td class=""><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></td>
    <td class=""><b><?php echo ucwords($row['name']) ?></b></td>
    <td class=""><b><?php echo number_format($row['no_adult']) ?></b></td>
    <td class=""><b><?php echo number_format($row['no_child']) ?></b></td>
    <td><p><small><?php echo $row['ticket_for'] ?></small></p></td>
    <td class=""><b><?php echo ($row['type']) ?></b></td>
    <td class=""><b><?php echo ($row['name_promo']) ?></b></td>
    <!-- Removed the table cell for "Người bán" -->
    <!-- <td class=""><b><?php echo ($row['firstname']) ?></b></td> -->
    <td class="text-center">
      <div class="btn-group">
        <a href="index.php?page=edit_ticket&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat ">
          <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-danger btn-flat delete_ticket" data-id="<?php echo $row['id'] ?>">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </td>
  </tr>
  <?php endwhile; } ?>
</tbody>

			</table>
		</div>
	</div>
</div>
<style>
	table td{
		vertical-align: middle !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
		$('.view_tickets').click(function(){
			uni_modal("Chi tiết vé","view_ticket.php?id="+$(this).attr('data-id'),"large")
		})
	$('.delete_ticket').click(function(){
	_conf("Bạn có chắc chắn muốn xóa vé này không?","delete_ticket",[$(this).attr('data-id')])
	})
	})
	function delete_ticket($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_ticket',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dữ liệu đã được xóa thành công",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>
