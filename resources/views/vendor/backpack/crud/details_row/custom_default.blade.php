<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-sm table-bordered">
                <colgroup>
                    <col class="col-md-2">
                    <col class="col-md-10">
                </colgroup>
                <tbody>
                    
                  @foreach ($customEntry as $label => $value)
                    <tr>
                      <th>{!! $label !!}</td>
                      <td>
                        {!! $value !!}
                      </td>
                    </tr>

                  @endforeach
                  
                  </tbody>
              </table>

		</div>
	</div>
</div>
<div class="clearfix"></div>