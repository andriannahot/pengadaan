
<!-- Modal -->
<div class="modal fade" id="ubahPasswordAdm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Password Admin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="/ubahPasswordAdm" method="post" role="form">
        {{csrf_field()}}
      <div class="modal-body">
      
                <div class="card-body">
                   
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password Lama</label>
                    <input type="password" class="form-control" id="passwordlama" name="passwordlama" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                  </div>
                 </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>