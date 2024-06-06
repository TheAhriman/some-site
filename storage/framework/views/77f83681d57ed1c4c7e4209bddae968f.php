<?php $__env->startSection('main-content'); ?>
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">color Lists</h6>
      <a href="<?php echo e(route('colors.create')); ?>" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Add color</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <?php if(count($colors)>0): ?>
        <table class="table table-bordered" id="color-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Photo</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S.N.</th>
              <th>Name</th>
              <th>Photo</th>
            </tr>
          </tfoot>
          <tbody>

            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($color->id); ?></td>
                    <td><?php echo e($color->name); ?></td>
                    <td>
                        <?php if($color->path): ?>
                            <?php
                              $photo=explode(',',$color->path);
                              // dd($photo);
                            ?>
                            <img src="<?php echo e($photo[0]); ?>" class="img-fluid zoom" style="max-width:80px" alt="<?php echo e($color->photo); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('backend/img/thumbnail-default.jpg')); ?>" class="img-fluid" style="max-width:80px" alt="avatar.png">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('colors.edit',$color->id)); ?>" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="<?php echo e(route('colors.destroy',[$color->id])); ?>">
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('delete'); ?>
                          <button class="btn btn-danger btn-sm dltBtn" data-id=<?php echo e($color->id); ?> style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
        <span style="float:right"><?php echo e($colors->links()); ?></span>
        <?php else: ?>
          <h6 class="text-center">No colors found!!! Please create color</h6>
        <?php endif; ?>
      </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link href="<?php echo e(asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s; /* Animation */
      }

      .zoom:hover {
        transform: scale(5);
      }
  </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

  <!-- Page level plugins -->
  <script src="<?php echo e(asset('backend/vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
  <script src="<?php echo e(asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?php echo e(asset('backend/js/demo/datatables-demo.js')); ?>"></script>
  <script>

      $('#color-dataTable').DataTable( {
        "scrollX": false
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[10,11,12]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){

        }
  </script>
  <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
            var form=$(this).closest('form');
              var dataID=$(this).data('id');
              // alert(dataID);
              e.preventDefault();
              swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Your data is safe!");
                    }
                });
          })
      })
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/scarycolony/PhpstormProjects/Complete-Ecommerce-in-laravel-10/resources/views/backend/colors/index.blade.php ENDPATH**/ ?>