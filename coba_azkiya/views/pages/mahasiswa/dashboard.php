<?php
include '../../../config/connection.php';
include '../../../config/dataMahasiswa.php';
include '../../../models/MahasiswaModel.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <!-- css -->
  <?php include('css.php') ?>
  <!-- css -->
</head>

<body>
  <div class="wrapper">
    <div class="main-panel">
      <!-- sidebar -->
      <?php include('../../layouts/sidebar.php') ?>
      <!-- sidebar -->

      <!-- navbar -->
      <?php include('../../layouts/header.php') ?>
      <!-- navbar -->

      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h2 class="fw-bold mb-3">Dashboard</h2>
              <h6 class="op-7 mb-2"><?php echo $nim ?> / <?php echo $nama ?></h6>
            </div>
          </div>

          <!-- Statistik Card -->
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-primary bubble-shadow-small">
                        <i class="fas fa-folder-open"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Total Tanggungan</p>
                        <h4 class="card-title"><?= $tanggunganCount['total_tanggungan']; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-success bubble-shadow-small">
                        <i class="fas fa-check"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Terpenuhi</p>
                        <h4 class="card-title"><?= $tanggunganCount['terpenuhi']; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-warning bubble-shadow-small">
                        <i class="fas fa-exclamation"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Belum Terpenuhi</p>
                        <h4 class="card-title"><?= $tanggunganCount['belum_terpenuhi']; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-secondary bubble-shadow-small">
                        <i class="fas fa-pen"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Status</p>
                        <h4 class="card-title">
                          <?php
                          if ($tanggunganCount['belum_terpenuhi'] > 0) {
                            echo 'Belum';
                          } else {
                            echo 'Terpenuhi';
                          }
                          ?>
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Table with Tanggungan Details -->
          <div class="col-md-12">
            <div class="card card-round">
              <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                  <div class="card-title">Tanggungan Mahasiswa</div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <?php if (empty($tanggunganData)): ?>
                    <div class="alert alert-info">Tidak ada tanggungan untuk mahasiswa ini.</div>
                  <?php else: ?>
                    <table class="table align-items-center mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th scope="col">No</th>
                          <th scope="col">Tanggungan</th>
                          <th scope="col">Keterangan</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Projects table -->
                        <?php
                        $no = 1;
                        foreach ($tanggunganData as $data): ?>
                          <tr>
                            <th><?= $no++; ?></th>
                            <td><?= htmlspecialchars($data['jenis_tanggungan']); ?></td>
                            <td><?= htmlspecialchars($data['keterangan']); ?></td>
                            <td>
                              <?php if ($data['status'] === 'terpenuhi'): ?>
                                <span class="badge badge-success">Terpenuhi</span>
                              <?php elseif ($data['status'] === 'pending'): ?>
                                <span class="badge badge-info">Pending</span>
                              <?php else: ?>
                                <span class="badge badge-danger">Belum Terpenuhi</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include('../../layouts/footer.php') ?>
    </div>
  </div>

  <?php include('js.php') ?>
</body>

</html>