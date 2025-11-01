<table>
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">{{ __('Property') }}</th>
        <th scope="col">{{ __('Property Purpose') }}</th>
        <th scope="col">{{ __('Name') }}</th>
        <th scope="col">{{ __('Email ID') }}</th>
        <th scope="col">{{ __('Phone') }}</th>
        <th scope="col">{{ __('Added By') }}</th>
        <th scope="col">{{ __('Status') }}</th>
        <th scope="col">{{ __('Inquiry Date') }}</th>

    </tr>
    </thead>
    <tbody>
        @foreach ($inquiry as $message)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $message->property->propertyContent->title ?? '' }}</td>
                <td>{{ $message->property->purpose }}</td>
                <td>{{ $message->name }}</td>
                <td>{{ $message->email }}</td>
                <td>{{ $message->phone }}</td>
                <td>
                    @if ($message->vendor_id != 0)
                        {{ @$message->vendor->username }}
                    @else
                        {{ __('Admin') }}
                    @endif
                </td>
                <td>
                  @if($message->inquiryStatus)
                      {{ $message->inquiryStatus->name}}
                  @else
                      {{ 'Pending' }}
                  @endif
                </td>
                <td>{{ $message->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
