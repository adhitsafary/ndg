@extends($layout)

@section('konten')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Generator ID</h1>
            <a href="{{ route('generator_id.create') }}" class="btn btn-primary mb-3">Buat Generator ID</a>
            <div class="row">
                @foreach ($generatorIds as $generatorId)
                <div class="col-md-4 col-sm-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $generatorId->getFullIdAttribute() }}</h5>

                            <a href="{{ route('generator_id.edit', $generatorId->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('generator_id.destroy', $generatorId->id) }}" method="POST" class="d-inline">
                                @csrf
                              
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
