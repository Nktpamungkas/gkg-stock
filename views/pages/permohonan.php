<?PHP
//error_reporting(0);
session_start();
// instance objek
$permohonan = new Permohonan();
$db = new Database();
//$idsub =$_SESSION['subQC'];
//$cek= $barang->jmlStock($idsub);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buka Permohonan</title>
</head>

<body>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
<div class="box-header">
  <a href="#" data-toggle="modal" data-target="#DataPermohonan" class="btn btn-success <?php if($_SESSION['lvlQC']==3){echo "disabled"; } ?>"><i class="fa fa-plus-circle"></i> Add</a>
  <?php if($cek>0){?><a href="cetak/lappermohonan/<?php echo $_SESSION['subQC']; ?>/" class="btn btn-danger pull-right" target="_blank">Cetak</a><?php } ?>
</div>
<div class="box-body">
<table width="100%" id="example1" class="table table-bordered table-hover">
 <thead class="btn-success">
  <tr>
    <th width="8%">No</th>
    <th width="15%">No Bon</th>
    <th width="16%">Dept</th>
    <th width="35%">Note</th>
    <th width="10%">Tanggal</th>
    <th width="16%">Action</th>
  </tr>
  </thead>
  <tbody>
  <?php
	  
$col=0;
$no=1;
foreach($permohonan->tampil_data($idsub) as $rowd){
	 $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
      ?>
  <tr bgcolor="<?php echo $bgcolor; ?>">
    <td><?php echo $no;?></td>
    <td><?php echo $rowd['documentno'];?> <a href="#" class="open_detailmohon" id="<?php echo $rowd['id'] ?>"><span class="label label-danger"><?php echo $rowd['jml'];?></span></a></td>
    <td align="left"><?php echo $rowd['dept'];?></td>
    <td align="center"><?php echo $rowd['note']; ?></td>
    <td align="right"><?php echo $rowd['tgl_mohon'];?></td>
    <td align="center"><div class="btn-group">
	  <a href="#" class="btn btn-warning btn-sm add_detail_permohonan <?php if($_SESSION['lvlQC']==3 ){echo "disabled"; } ?>" id="<?php echo $rowd['id'] ?>"><i class="fa fa-plus"></i> </a>		
      <a href="#" class="btn btn-info btn-sm open_editpermohonan <?php if($_SESSION['lvlQC']==3 ){echo "disabled"; } ?>" id="<?php echo $rowd['id'] ?>"><i class="fa fa-edit"></i> </a>
      <a href="#" class="btn btn-danger btn-sm <?php if($_SESSION['lvlQC']==3 or $rowd['idb']!=""){echo "disabled"; } ?>" onclick="confirm_delete1('./hapuspermohonan-<?php echo $rowd['id'] ?>/');"><i class="fa fa-trash"></i> </a></div>
    </td>
  </tr>
  <?php
  $no++;} ?>
  </tbody>
</table>
<?php
	/*function no_urut(){
		date_default_timezone_set("Asia/Jakarta");
		$format = "QCF/".date("y/m/");
		$sql=mysql_query("SELECT documentno FROM tbl_permohonan WHERE substr(documentno,1,10) like '".$format."%' ORDER BY documentno DESC LIMIT 1 ") or die (mysql_error());
		$d=mysql_num_rows($sql);
		if($d>0){
			$r=mysql_fetch_array($sql);
			$d=$r['documentno'];
			$str=substr($d,10,3);
			$Urut = (int)$str;
		}else{
			$Urut = 0;
		}
		$Urut = $Urut + 1;
		$Nol="";
		$nilai=3-strlen($Urut);
		for ($i=1;$i<=$nilai;$i++){
			$Nol= $Nol."0";
		}
		$nipbr =$format.$Nol.$Urut;
		return $nipbr;
	}
	$nou=no_urut();*/
	?>
<div class="modal fade" id="DataPermohonan">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="input-permohonan/" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Data Permohonan</h4>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="id" name="id">
                  <input type="hidden" id="idsub" name="idsub" value="<?php echo $_SESSION['subQC'];?>">
				  <input type="hidden" id="documentno" name="documentno" value="<?php echo $nou;?>">
                  <div class="form-group">
                  <label for="dept" class="col-md-3 control-label">Dept</label>
                  <div class="col-md-3">
                  <select name="dept" class="form-control select2" id="dept">
					  <option value="QCF">QCF</option>
				  </select>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="nama" class="col-md-3 control-label">Note</label>
                  <div class="col-md-8">
                  <textarea name="note" id="note" class="form-control"></textarea>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="jenis" class="col-md-3 control-label">Tgl Permohonan</label>
                  <div class="col-md-3">
                  <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input name="tgl_mohon" type="text" class="form-control pull-right" id="datepicker" placeholder="yyyy-mm-dd" value="<?php echo $Awal; ?>" autocomplete="off" required/>
                  </div>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" >Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
</div>
<!-- Modal Popup untuk delete-->
<div class="modal fade" id="modal_delete_permohonan" tabindex="-1" >
  <div class="modal-dialog modal-sm" >
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h4>
      </div>

      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <a href="#" class="btn btn-danger" id="delete_link1">Delete</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Popup untuk Edit-->
<div id="PermohonanEdit" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="PermohonanDetailAdd" class="modal fade modal-3d-slit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="PermohonanDetail" class="modal fade modal-3d-slit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>	
</div>
</body>
<!-- Javascript untuk popup modal Delete-->
<script type="text/javascript">
    function confirm_delete1(delete_url)
    {
      $('#modal_delete_permohonan').modal('show', {backdrop: 'static'});
      document.getElementById('delete_link1').setAttribute('href' , delete_url);
    }

</script>
</html>