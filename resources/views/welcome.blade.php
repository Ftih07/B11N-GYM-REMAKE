<div class="layout">
    <div class="stairs">Tangga</div>
    <div class="bathrooms">
        <div class="bathroom">Kamar mandi 01</div>
        <div class="bathroom">Kamar mandi 02</div>
    </div>
    <div class="container-room">
        @foreach(array_chunk(range(1, 10), 2) as $row)
        <div class="room-row">
            @foreach($row as $room)
            @php
            $isBooked = in_array($room, $bookedRooms ?? []);
            @endphp
            <div class="room {{ $isBooked ? 'booked' : 'available' }}">
                {{ str_pad($room, 2, '0', STR_PAD_LEFT) }}
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    <div class="balcony">Balkon</div>
</div>