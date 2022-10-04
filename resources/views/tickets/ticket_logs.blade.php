<div class="col-lg-12">
    <div class="timeline timeline-inverse">
        @if ($ticketLogs == null)
            <p><i>No ticketLogs recorded</i></p>
        @else
            @php
                $i = 0;
            @endphp
            @foreach ($ticketLogs as $item)
                <div class="time-label" style="font-size: .9em !important;">
                    <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->Log }}
                    </span>
                </div>
                <div>
                <i class="fas fa-info-circle bg-primary"></i>

                <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>

                        <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>

                        @if ($item->LogDetails != null)
                            <div class="timeline-body" style="font-size: .9em !important;">
                                <?= $item->LogDetails ?>
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