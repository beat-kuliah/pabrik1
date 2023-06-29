<div class="modal fade" id="createGudang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Gudang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="formGudang" id="formGudang">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" name="kode_barang" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Vendor</label>
                        <div class="col-sm-10">
                            <select name="vendor" class="form-select" id="selectVendor">
                                <option value="" disabled selected>Select Vendor</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Alamat1</label>
                        <div class="col-sm-10">
                            <input type="text" name="alamat1" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Alamat2</label>
                        <div class="col-sm-10">
                            <input type="text" name="alamat2" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Alamat3</label>
                        <div class="col-sm-10">
                            <input type="text" name="alamat3" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="tambahGudang()">Tambah</button>
            </div>
        </div>
    </div>
</div>