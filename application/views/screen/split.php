<div class="row">
  <div class="col-md-6">
    <div class="main-box">
      <header class="main-box-header clearfix">
        <h2></h2>
      </header>
      
      <div class="main-box-body clearfix">
        <?php echo $form ?>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="main-box clearfix">
      <header class="main-box-header clearfix">
        <!-- <h5>Dados cadastrados, marque os campos ao lado do ícone <i class='fa fa-pencil'></i> e clique em <span class="btn btn-xs btn-danger">Deletar</span> para remover o item.</h5> -->
        <h5>
          Marque os itens e clique em deletar para remover 
        </h5>
      </header>
      <div class="main-box-body clearfix">
        <div class="table-responsive">
          <?php  echo $table?>

        </div>
      </div>
    </div>            
  </div>
</div>




<script>
  $(document).ready(function() {
    var table = $('#data-table').dataTable({
      'info': false,
      'sDom': 'lTfr<"clearfix">tip',
      'oTableTools': {
              'aButtons': [
                  {
                      'sExtends':    'collection',
                      'sButtonText': '<i class="fa fa-cloud-download"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-down"></i>',
                      'aButtons':    [  ]
                  }
              ]
          }
    });
    
      var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.dataTables_wrapper');
    
    var tableFixed = $('#table-example-fixed').dataTable({
      'info': false,
      'pageLength': 50
    });
    
    new $.fn.dataTable.FixedHeader( tableFixed );
  });
  </script>