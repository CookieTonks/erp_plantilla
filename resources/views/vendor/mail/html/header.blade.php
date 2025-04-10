@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="" class="../logo.png" alt="ERP BADILSA">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>