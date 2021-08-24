<!-- Modal -->
<div class="modal fade" id="pengajuanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pengajuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/tambahPengajuan" method="post" role="form" enctype="multipart/form-data">
        {{csrf_field()}}

        <input type="hidden" name="id_pengadaan" class="id_pengadaan">
      <div class="modal-body">
      
                <div class="card-body">
                   <div class="form-group">
                    <label for="nama">Nama Pengadaan</label>
                    <input type="text" class="form-control nama_pengadaan" id="nama_pengadaan" name="nama_pengadaan" placeholder="Masukan Nama Pengadaan" disabled="">
                  </div>
                  
                  <div class="form-group">
                    <label for="gambar">Proposal</label>
                    <input type="file" class="form-control" id="proposal" name="proposal" accept="application/pdf">
                  </div>

                  <div class="form-group">
                    <label>Anggaran : <input type="" class="labelRp" disable style="border:none; background-color: white; color: black;"></label>
                    <input type="text" class="form-control anggaran" id="anggaran" name="anggaran" placeholder="Masukan Anggaran" onkeyup="curency()">
                  </div>
                 
                 </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </div>
  </div>
</div>