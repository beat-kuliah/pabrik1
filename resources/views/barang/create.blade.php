<div class="modal fade" id="createBarang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="formBarang" id="formBarang">
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
                        <label class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" onkeypress="return isNumberKey(event)" name="harga" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                            <input type="text" onkeypress="return isNumberKey(event)" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Gudang</label>
                        <div class="col-sm-10">
                            <select name="gudang" class="form-select" id="selectGudang">
                                <option value="" disabled selected>Select Gudang</option>
                            </select>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="tambahBarang()">Tambah</button>
            </div>
        </div>
    </div>
</div>