@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header"><strong>{{!empty($oneItem) ? 'Chỉnh sửa' : 'Thêm mới'}} Banner</strong></div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <b>Tiêu đề Banner</b>
                                                <input name="title" class="form-control" value="{{$oneItem->title ?? ''}}" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <b>Kiểu banner:</b>
                                                <select name="type" class="form-control" onchange="this.form.submit()">
                                                    <option type="text" value="default" {{$type == 'default' ? 'selected' : ''}}>default</option>
                                                    <option type="text" value="content" {{$type == 'content' ? 'selected' : ''}}>content</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Website</label>
                                                <select name="id_website" class="form-control" required>
                                                    @foreach($allSite as $item)
                                                        <option @if($id_website == $item->id) selected @endif
                                                        value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Vị trí</label>
                                                <select name="id_position" class="form-control" required>
                                                @foreach($allPosition as $item)
                                                    <option @if($id_position == $item->id) selected @endif
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <span>STT</span>
                                            <input required type="number" class="form-control" min="1" name="order" value="{{ $oneItem->order ?? 1 }}">
                                        </div>
                                        <div class="col-3">
                                            <span>Start date</span>
                                            <input type="datetime-local" class="form-control" name="start_date" value="{!! !empty($oneItem->start_date) ? date('Y-m-d\TH:i', strtotime($oneItem->start_date)) : ''!!}">
                                        </div>
                                        <div class="col-3">
                                            <span>End date</span>
                                            <input type="datetime-local" class="form-control" name="end_date" value="{!! !empty($oneItem->end_date) ? date('Y-m-d\TH:i', strtotime($oneItem->end_date)) : ''!!}">
                                        </div>
                                        <div class="col-3">
                                            <div>On/Off</div>
                                            <input type="checkbox" name="status" value="1" @if($oneItem->status ?? 1 != 0) checked @endif>
                                        </div>
                                        @php if($type == 'default')
                                            echo view('admin.banner.type.default', ['oneItem' => $oneItem ?? []]);
                                        else
                                            echo view('admin.banner.type.content', ['oneItem' => $oneItem ?? []]);
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <input type="submit" name="clickSubmit" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
