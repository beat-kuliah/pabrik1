<div class="modal fade" id="editPenjualan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Data Penjualan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="formEditPenjualan" id="formEditPenjualan">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" id="editTanggal" name="tanggal" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Barang</label>
                        <div class="col-sm-10">
                            <select name="barang" class="form-select" id="selectEditBarang">
                                <option value="" disabled selected>Select Barang</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" id="editNama" readonly class="form-control-plaintext" name="nama" class="form-control" value="Barang" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" id="editHarga" readonly class="form-control-plaintext" name="harga" class="form-control" value="0" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                            <input type="text" id="editStok" readonly class="form-control-plaintext" name="stok" class="form-control" value="0" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Terjual</label>
                        <div class="col-sm-10">
                            <input type="text" onkeypress="return isNumberKeyCheck(event)" id="editTerjual" name="terjual" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Total</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="editTotal" name="total" class="form-control" value="0" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updatePenjualan()">Update</button>
            </div>
        </div>
    </div>
</div>