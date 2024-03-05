<?PHP
error_reporting(0);
session_start();

$barang     = new Barang();
$barangout  = new BarangKeluar();
$db         = new Database();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>lapstok-out</title>
</head>

<body>
  <?php
$Awal	  = isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$cek    = $barangout->cektgl($Awal,$Akhir,$idsub);
?>
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title"> Filter Detail Barang Keluar</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
      <div class="box-body">
        <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" />
          </div>
        </div>
        <!-- /.input group -->
      </div>
      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off" />
          </div>
        </div>
        <button type="submit" class="btn btn-info">Search</button>
        <!-- /.input group -->
      </div>
        <div class="col-sm-2">

        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">

      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Details Data</h3> <?php if($cek>0){?><a href="cetak/lapstokout/<?php echo $Awal; ?>/<?php echo $Akhir; ?>/<?php echo $idsub; ?>" class="btn btn-danger pull-right" target="_blank">Cetak</a> <?php } ?>
    </div>
    <div class="box-body">

      <table id="example2" class="table table-bordered table-hover display nowrap" width="100%">
        <thead class="btn-warning">
          <tr>
            <th width="31">No</th>
            <th width="160">Tanggal</th>
            <th width="160">Kode</th>
            <th width="216">Nama</th>
            <th width="220">Jumlah</th>
            <th width="220">Satuan</th>
            <th width="269">Harga</th>
            <th width="269">Total</th>
            <th width="169">Note</th>
            <th width="169">UserID</th>
          </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
	  foreach($barangout->tampildataout_tgl($Awal,$Akhir,$idsub) as $row){
		$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
  ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center">
              <?php echo $no; ?>
            </td>
            <td align="center">
              <?php echo $row['tanggal']; ?>
            </td>
            <td align="center">
              <?php echo $row['kode']; ?>
            </td>
            <td align="left">
              <?php echo $row['nama']; ?>
            </td>
            <td align="right">
              <?php echo $row['jumlah']; ?>
            </td>
            <td align="center">
              <?php echo $row['satuan']; ?>
            </td>
            <td align="right">
              <?php echo $row['harga']; ?>
            </td>
            <td align="right">
              <?php echo $row['total_harga']; ?>
            </td>
            <td align="left">
              <?php echo $row['note']; ?>
            </td>
            <td align="center">
              <?php echo $row['userid']; ?>
            </td>
          </tr>
          <?php $no++; } ?>
        </tbody>
      </table>

    </div>
  </div>
</body>

</html>
