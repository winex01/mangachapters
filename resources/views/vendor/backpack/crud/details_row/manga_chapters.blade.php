<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-sm table-bordered">
                <tbody>
                   
                    @php
                        $chapters = $entry->chapters->sortByDesc('chapter')->take(10);
                    @endphp

                    @if ($chapters->count() > 0)
                        <tr><td>{{ $entry->title }}</td></tr>
                        <tr><td>Show only latest 10 chapters.</td></tr>
                    @else
                        <tr><td>No results.</td></tr>
                    @endunless

                    @foreach ($chapters as $chapter)
                        <tr>
                            <td>
                                {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
                                    'chapter' => $chapter->chapter, 
                                    'release' => $chapter->release, 
                                ]) ) !!}
                            </td>
                        </tr>
                    @endforeach
                  
                  </tbody>
              </table>

		</div>
	</div>
</div>
<div class="clearfix"></div>