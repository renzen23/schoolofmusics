@wrapper(['block' => $block])
<div id="listing" data-livestream="{{ $block->livestream_event_url }}">
    <events-directory events-subscription-link="{{ get_field('events_subscription_link', 'option') }}"></events-directory>
</div>
@endwrapper