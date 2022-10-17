<div class="row">
    {{-- TIMELINE --}}
    <div class="timeline timeline-inverse">
        @if ($timeFrame == null)
            <p><i>No timeframe recorded</i></p>
        @else
            @php
                $i = 0;
            @endphp
            @foreach ($timeFrame as $item)
                <div class="time-label" style="font-size: .9em !important;">
                    <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->Status }}
                    </span>
                </div>
                <div>
                <i class="fas fa-info-circle bg-primary"></i>

                <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>

                        <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>

                        @if ($item->Notes != null)
                            <div class="timeline-body" style="font-size: .9em !important;">
                                <?= $item->Notes ?>
                            </div>
                        @endif
                        
                    </div>
                </div>
                @php
                    $i++;
                @endphp
            @endforeach
        @endif
    </div>
</div>