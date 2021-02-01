@component('mail::message')

@component('mail::table')
  |        |          |
  | ------------- |:-------------:|
  | @if ($data['service_title']) Service @endif     | {{ $data['service_title'] }}  |
  | @if ($data['service_area']) Service area @endif     | {{ $data['service_area'] }}  |
  | @if ($data['service_price']) Service price @endif     | {{ $data['service_price'] }}  |
  | @if ($data['user_name']) Ditt navn @endif       | {{ $data['user_name'] }}      |
  | @if ($data['user_mail']) E-post @endif          | {{ $data['user_mail'] }}      |
  | @if ($data['topic']) Emne @endif                | {{ $data['topic'] }}          |
  | @if ($data['phone']) Telefonen @endif               | {{ $data['phone'] }}      |
  | @if ($data['your_message']) Melding @endif      | {{ $data['your_message'] }}   |

@endcomponent


@endcomponent
