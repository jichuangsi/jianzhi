
<!-- {!! Theme::get('site_config')['statistic_code'] !!} -->
{!! Theme::widget('popup')->render() !!}
<!-- @if(Theme::get('is_IM_open') == 1)
{!! Theme::widget('im',
array('attention' => Theme::get('attention'),
'ImIp' => Theme::get('basis_config')['IM_config']['IM_ip'],
'ImPort' => Theme::get('basis_config')['IM_config']['IM_port']))->render() !!}
@endif -->