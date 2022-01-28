@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'societydocument/';
?>



<div class="content-page">

	<!-- Start content -->
	<div class="content">

		<div class="container-fluid">

			<div class="row">
				<div class="col-xl-12">
					<div class="breadcrumb-holder">
						<h1 class="main-title float-left"> Documents</h1>
						<ol class="breadcrumb float-right">
							<li class="breadcrumb-item">Home</li>
							<li class="breadcrumb-item active"> Documents</li>
						</ol>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<!-- end row -->
					@include('snippets.errors')
						@include('snippets.flash')
			<div class="row">

				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<div class="card mb-3">
						<div class="card-header">
							<h3> Documents </h3>
							 <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>

							<form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
								{{ csrf_field() }}

								<div class="row">

									<div class="col-sm-12 col-md-6">
										<div class="input-group mb-3">
											<div class="input-group-prepend"><span class="input-group-text" id="inputGroupFileAddon01">Upload</span></div>
											<div class="custom-file">
												<input class="custom-file-input" id="inputGroupFile01" type="file" aria-describedby="inputGroupFileAddon01" multiple name="file[]">
												<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
											</div>
										</div>
									</div>

									<span class="pull-right">
										<button class="btn btn-primary" type="submit">Submit</button>
									</span>

								</div>

							</form>



						</div>

						<div class="card-body">
							<div class="table-responsive">
							<table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
									<thead>
										<tr>
										<th scope="col">#</th>
										<th scope="col">File</th>
										<th scope="col">Date Created</th>
										</tr>
									</thead>
									<tbody>
										<?php
									if(!empty($documents)){
										$storage = Storage::disk('public');
										$path = 'societydocument/';
										$i =1;
										foreach ($documents as $document){
											?>

											<tr>
												<td>{{$i++}}</td>
												<td>
													<?php
													$file = $document->file;

													if(!empty($file)){
														if($storage->exists($path.$file))
															{ 
																?>
																<div class="col-md-2 image_box">
																	<a target="_blank" href="{{ url('storage/app/public/'.$path.'/'.$file) }}">
																		<img src="{{ url('public/assets/images/file.png') }}" style="width: 50px;"><br>
																	</a>
																</div>
															<?php }
														}
														?>
													</td>
													<td>{{date('d M Y',strtotime($document->created_at))}}</td>
												</tr>
												<?php
											}}
											?>


											</tbody>
										</table>
									</div>
									<!-- end table-responsive-->

								</div>
								<!-- end card-body-->

							</div>
							<!-- end card-->

						</div>

					</div>
					<!-- end row-->

				</div>
				<!-- END container-fluid -->

			</div>
			<!-- END content -->

		</div>




		@include('admin.common.footer')
