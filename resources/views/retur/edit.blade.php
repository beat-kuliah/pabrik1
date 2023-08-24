<div class="modal fade" id="updateRetur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Retur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="formEditRetur" id="formEditRetur">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Kode</label>
                        <div class="col-sm-9">
                            <input type="text" name="edit_kode" id="edit_kode" class="form-control-plaintext">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="edit_nama" id="edit_nama" class="form-control-plaintext">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="text" name="edit_jumlah" id="edit_jumlah" class="form-control-plaintext">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Stok</label>
                        <div class="col-sm-9">
                            <input type="text" name="edit_stok" id="edit_stok" class="form-control-plaintext">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Jumlah Retur</label>
                        <div class="col-sm-9">
                            <input type="number" name="edit_jmlretur" id="edit_jmlretur" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editRetur()">Update</button>
            </div>
        </div>
    </div>
</div>