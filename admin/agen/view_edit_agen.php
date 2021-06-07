<div class="content-wrapper mt-3">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tambah Agen</h3>
            </div>

            <form action="<?= base_url('admin/edit_agen') ?>" method="post" class="form-horizontal" enctype="multipart/form-data">

                <input type="hidden" name="id_agen" value="<?= $record['id_agen']; ?>">

              <div class="card-body">

                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Nama Agen</label>
                  <div class="col-sm-6">
                    <input type='text' class='form-control' name='nama_agen' value="<?= $record['nama']; ?>" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Alamat</label>
                  <div class="col-sm-6">
                    <input type='text' class='form-control' name='alamat_agen' value="<?= $record['alamat']; ?>" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Gambar</label>
                  <img src="<?= base_url('assets/images/agen/').$record['foto']; ?>" width="200px" />
                  <div class="col-sm-6">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="customFileLangHTML" name="foto_agen">
                      <label class="custom-file-label" for="customFileLangHTML" data-browse="Cari">Pilih gambar...</label>
                    </div>
                  </div>
                </div>


                <div class="form-group row">
                  <label class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-10">
                    <button type='submit' name='submit' class='btn btn-primary btn-sm'>Simpan</button>
                    <a href='<?= base_url('admin/agen') ?>'><button type='button' class='btn btn-secondary btn-sm ml-1'>Batal</button></a>
                  </div>
                </div>

              </div>

            </form>



          </div>
        </div>
      </div>
    </div>
  </section>
</div>