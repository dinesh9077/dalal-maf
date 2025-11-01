<table>
    <thead>
    <tr>
        <th>Title</th>
        <th>Post By</th>
        <th>Type</th>
        <th>Category</th>
        <th>Purpose</th>
        <th>Price</th>
        <th>Country</th>
        <th>State</th>
        <th>City</th>
        <th>Area</th>
    </tr>
    </thead>
    <tbody>
    @foreach($properties as $property)
        <tr>
            <td>{{ $property->propertyContents->first()->title ?? '' }}</td>
            <td>{{ $property->vendor->username ?? 'Admin' }}</td>
            <td>{{ $property->type }}</td>
            <td>{{ $property->categoryContent->name ?? '' }}</td>
            <td>{{ $property->purpose }}</td>
            <td>{{ $property->price }}</td>
            <td>{{ $property->countryContent->name ?? '' }}</td>
            <td>{{ $property->stateContent->name ?? '' }}</td>
            <td>{{ $property->cityContent->name ?? '' }}</td>
            <td>{{ $property->areaContent->name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
