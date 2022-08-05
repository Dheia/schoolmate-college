@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Products And Services
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Products and Services</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProdServices">Add Products & Services</button>
            {{-- <a href="{{ url()->current() }}/create" class="btn btn-primary">Add Products & Services</a> --}}
          </div>
        
        <div class="box-body">
          {{-- {{ dd($customers) }} --}}

          <table class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Name</th>
              <th>SKU</th>
              <th>Type</th>
              <th>Sales Description</th>
              <th>Sales Price</th>
              <th>Cost</th>
              <th>Qty On Hand</th>
              {{-- <th>Reorder Point</th> --}}
              <th>Action</th>
            </thead>  
            <tbody>
              @foreach($items as $item)
                <tr>
                  <td>{{ $item->Name }}</td>
                  <td>{{ $item->Sku }}</td>
                  <td>{{ $item->Type }}</td>
                  <td>{{ $item->Description }}</td>
                  <td>{{ $item->UnitPrice }}</td>
                  <td>{{ $item->PurchaseCost }}</td>
                  <td>{{ $item->QtyOnHand }}</td>
                  <td>Action</td>
                </tr>
              @endforeach
            </tbody>
          </table>
            
        </div>

      </div>
      </div>
    </div>



  <!-- Modal -->
  <div id="addProdServices" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Products and Services Information</h4>
        </div>
        <div class="modal-body" style="padding: 0;">
          <table class="table m-b-0">
            <tbody>
              <tr>
                <td style="width: 20%; vertical-align: middle;">
                  <div class="circle" style="background: #0077c5;
                                              background-position: 14px;
                                              display: inline-block;
                                              width: 84px;
                                              height: 84px;
                                              border-radius: 50%;
                                              position: relative;
                                              transition: all .08s ease-in;
                                              left: 50%;
                                              transform: translate(-50%);">
      
                    <img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAnhJREFUeNrsWYFxgzAMBI4B2KBsULIBmaDJBE0maDJByQSkE6QbkE5AOgHZgG5ANkilO/uquBiMMYXmrDsdCRjZL0sv2ziOFStWJiXX63UBegCtQFNyH/93lYrZWowBJCZAbgbVE1QdwHhoMAFoJnRe4gyxK8qqh/1IsMUF+wyGAlSQjtCLEXmeEJCBgf4i1geXwjgwMJiTsIgkbQoygFDRUWi3aAl1HsqZSUBpGyDi3Yq0TevakzCrxFxssMtlY4rduCwU2odkVsXcK2vuF02OInZX5J24D6CQeDPVYMhMwoJa1E1yrNLKL4EYCkOJHxkkq1zHQD+vDFdWIq3oMRa/w65ilPNc9EQy4eUZZeSwLWbLpprAyCPrs3LoGDHS1YRQFwOZkaxtVcBYTS9R9Qt+3DAJPKoO/L5HvQIXHp9L13UvU98hsDEu2d8Vzy+PtHlm1x00Pv+XrQ+M9QSXPcXgk+d8Zh502AguLx1f+wL9gEEdDWALBQy1NJnLGKUup2q2I10k6ZFTkbBziG9mCj0GN9fwEwsaPsTGa0VPbkHfOnr3CXTDZjjRiA589xU0YDO0ZqEopWxx3xQMwX68gy4zVbNRzVW2OeKmj1N8NDYoYWEgDV2vgVXwhTlL6JDQ/ZjCww3HNGNj/CV+G12CN2ZYA0CPEwC1A/0EfW+qo75igdtPpCZh/Wytod49nkXeJSjfhBFGqV1X7Y+1q4CpgAI5sIKtI9upgtpqUv5JugoYG5QqK1misKAsKAvKgrKgLCgLyoKyoKYEKlQ+mtLbo+HpldaXR1ezw9L5OeodWs6wC5j9xUzhl4ahT5curI+5Y8WKlVHlW4ABABOTdFCPc9wyAAAAAElFTkSuQmCC" alt="inventory">
                  </div>
                </td>
                <td style="vertical-align: middle;">
                  <a href="{{ url('admin/quickbooks/products-and-services/inventory') }}"><b>Inventory</b></a>
                  <p>Products you buy and/or sell and that you track quantities of.</p>
                </td>
              </tr>

              <tr>
                <td style="width: 20%; vertical-align: middle;">
                  <div class="circle" style="background: #0077c5;
                                              background-position: 14px;
                                              display: inline-block;
                                              width: 84px;
                                              height: 84px;
                                              border-radius: 50%;
                                              position: relative;
                                              transition: all .08s ease-in;
                                              left: 50%;
                                              transform: translate(-50%);">
      
                    <img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAzZJREFUeNrsWottpDAQXaItgBKcDihhO7h0ENIBHRwdUAJJBVwq8F4F0AFJBVwHnH0aR5OJv9hk4cRIFhJrxvM8Hz/bezodcsghKSVbU/k8zxfxeBSNoddvor1kWXbd1UwJMKVo42wXDqB3B2YSrZHGo9ZqwD3sBUwtWm7ozwDchL6R35e3BpKD4dQwI5gAHeVWwJSoTwGewN7rTMYadE4hE7QaGOhXeRSIPFUox4Chyd3rZh0Mwp4pUA7hCZHAWMo8DQFUEM8YyzCAV30rT33O/AFwHNuQClDvWlNQ2PWeRmJwjYc9H9GyFBAjgHIAycH43DJoFTBOZQMF41aagqMKEQsB1WFAmgKg4ptpvrkkyGNmWi7g91oB9OZrSBHzoD8tGJHHAkKL8uyosAz9fvFRrJKydQDnqbjcEn0IPPehOyq8cs9i4lXuLeP1Gs8XnkuNCs/S1lGFVr0gbBpNDnwpKobkV8SXBY6rcmt0dli6wCHWQQ3u4H0XwxUN4+kd4e3KdHuqMfE4X1NG7mesboxnJSV4pPTJlwVjqMn7ty87w3sVy0Py84IsG9bQS2QADIzOppLkdB+R4noFvTWyvaAdmrXoPmEjZUIwbv64Ft0ns9mvvaf7FnAEVDA/TLrt16z2U+AZRA2VlYJqI8BEeRvzwCbUc2j94AhUayLLnp5pfPjenSe+V1Ga78XzSTR5sirB/BTNxgge1bfo3Tt8L6U0gZF6QX8O/Z9g/NcUVYvrckDDqj95jmwNGPJUjRb6CfXXeYYbxo3e0nNbYhvA4aOxjhSKmjCAUXOoyR3jrQvKsR8aVd5oQBWa5Oee4zhBnRNRIRn3V1jRH4C2XMX7PybqJPreQ18J/BfQqSRyPiWUEJ4HgJ/XIIJ3p/9QfEGxjdj7A55vMaB+kzXnZgJFpyR2LQKlYl5dluU3AiTBcFiMB5GPz9EKY4ktLekBvI8S6z7ZxAIL0B5ipgaV4jJvidf4gkXTCermt4qGC+neclNoBLW5+1+LQZ9CRQfKcG6+nZt6yyFmgy8P0JVQu6v/VBjOx3swnL7vdvEHEQdzD66cizjod4SmeODzuMHE3g855JBtyl8BBgA6ROd+rgqYXgAAAABJRU5ErkJggg==" alt="inventory">
                  </div>
                </td>
                <td style="vertical-align: middle;">
                  <a href="{{ url('admin/quickbooks/products-and-services/non-inventory') }}"><b>Non-inventory</b></a>
                  <p>Products you buy and/or sell but don’t need to (or can’t) track quantities of, for example, nuts and bolts used in an installation.</p>
                </td>
              </tr>

              <tr>
                <td style="width: 20%; vertical-align: middle;">
                  <div class="circle" style="background: #0077c5;
                                              background-position: 14px;
                                              display: inline-block;
                                              width: 84px;
                                              height: 84px;
                                              border-radius: 50%;
                                              position: relative;
                                              transition: all .08s ease-in;
                                              left: 50%;
                                              transform: translate(-50%);">
      
                    <img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAeZJREFUeNrsmuttgzAQgHGV/6UTlA3CCGSCZoOSDdIN6AR0g7QTpJkAOgHpBGWDsAE9q2fpsExawLiA7qSTwcTmvpwfZxvPW6CIMSuv69qHZAv6SLIr0DchxPvs/i0ACkG/6nbJEHpWQBc0XoLtQSPUhDwr5gSVKaNN3tCgk7l4SUmg5dP7WHlyDlB7NPZI8o4ENCH5ylvh1KESajz2o4YYmmlk04abJc5To0PBfJRDkpOs57HfuXIywwuxwX5TwXW5CCgEOy+m+S2yTzEUQzEUQ5nktkcZq7HfoJWvDF4hedCyVRy3wWjiWvkYkgPe6r89QfmX/1ozmSTtUM/BRj2DPYUhT4H7DU+gNPQpu4ZCuM4KtOaYYiQiXHlJLSeyEd/RWKbw6MdQDMVQDMVQFuaWbd1PLnSjkz3FEQX3qWlJ3yhdrpkyJwb2iNJ5oPhtoNBPPP7yrO3kgwcKhmIohmKoOUBVY79g5RAmB12DTvObpCuTb4iTadihrhi/s/BtTb5DY79cHlLrYN7PDqtc9N17zZ1XU1P8xPRM994VUJ/YbyiUNGaHhsuDgshCQ1AH3uo0/84JFIIVnvkIpkLDPsh1mwSoa+JdKjuAenUJJftAioZI40/YHMuBdUo4H5tj6bEsWL4FGAAkdDGBLCz20wAAAABJRU5ErkJggg==" alt="service">
                  </div>
                </td>
                <td style="vertical-align: middle;">
                  <a href="{{ url('admin/quickbooks/products-and-services/service') }}"><b>Service</b></a>
                  <p>Services that you provide to customers, for example, landscaping or tax preparation services.</p>
                </td>
              </tr>

             {{--  <tr>
                <td style="width: 20%; vertical-align: middle;">
                  <div class="circle" style="background: #0077c5;
                                              background-position: 14px;
                                              display: inline-block;
                                              width: 84px;
                                              height: 84px;
                                              border-radius: 50%;
                                              position: relative;
                                              transition: all .08s ease-in;
                                              left: 50%;
                                              transform: translate(-50%);">
      
                    <img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo4YWIzYTY5ZS0zODBkLTQwOTQtYTA1NC1mN2RhNWM0ZGUwOGYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTFBQjBDRjBBQjJEMTFFNEJFOEVDMjRDMzE5MTZDODgiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTFBQjBDRUZBQjJEMTFFNEJFOEVDMjRDMzE5MTZDODgiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4YWIzYTY5ZS0zODBkLTQwOTQtYTA1NC1mN2RhNWM0ZGUwOGYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OGFiM2E2OWUtMzgwZC00MDk0LWEwNTQtZjdkYTVjNGRlMDhmIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+5jYGCgAABAdJREFUeNrsmWlIVUEUx322W5l+sMUWLUMqMivC0hYtykwKKySwjRZIK4qKINskhSiyaCFLggqyDTJoEaOsD6WtVEpY9KWMqEyIFtMyLV//wf+DYXrvvsV7n2h34Me998zMnXtmOefMXIvVavVpa8nXpw0mUylTqaYUA/aBn+A3CJbysoFVgwaQC6YAi1utCkNhALGgzPpvWiSV2Wt1Pb0Eia62b4RCm0EjP6YG5IEzfC4HflLZbiBAoTcIAgngOPgqKZcNLN5Waq30AeKDAin3B+8pvwuG2KnbF5xkmRRF8QPSezO9qdRE8JsNb7WTH6X0eim4AE5R0T+Ui2uSnfqpUt1Z3lBKTJlKNnhOo9xgcEWanmoqATEa9Q+z3BcwyFE5iw4RRXtwE8SCF2AsqHFSpzctY38+fwCPQYWTeh1BMYgCZSAa1Blh/faw976DoQZZU5n+4BPbPGHE9BMj3cAG5hmoSAjoID1Pk9bgMiPW1ALF/+jNFH78EUWeQXkdGGm0n9KbJfz4S4rcFxQy756c526YJMKVcQx96khnKf+ok9CnFuwGo3UIwRrBat5HeRr7TQXPwH2wAXQi46Uy3Z28ww9sAk/AI1pAT5PozG28/+iJ9cuSfMl3hj4FfC5WFnEPO6FPEH1ZMjgPfkiOdr0H0y+SIZctLXB3TW2XKufwI4W8D6imvIBhjlq3H+M+EWnES/IgdowtrXZRKQs74RdlVYwR3bJ+CZL3t9dwIq2PrddFuHOacV+JEvrEaXRYPYh2Qam5UkdcZee45aeEb/jMFxzTKDcK3NbYNtxh3OfIz+Wz3DvQ04lSYeA6SNOK1ts7WITCAOSDQC7qNRoLtpQhUhiNRi8p9BFG5bXWdg4sBREgHJwF08EfB+VfMd+jTWIue0eMVKgXfNFwUMs2d7ropxxiz6QvBqnsxYXgDeXtQB8dt/rB0ja9HKzg/RYws1lvVrSMkMxtlpJ3iPJYHUZmDt+1Q5Hn2NlaNHukjoAuoAhkKnkDeB2owyiF8BqqyNfTKQeA/XqdJkXzmqaxWI1M9VLoE6OXUlW8bqQF9HbqytGyWU9dlErndSV4CIZ5UaEx4CmYz2A1Qy+l8sBs8AlE0ket8sKBajp9WjhHKB5c1vOEVrxsBLjBSDgHJBmolHAhu3jWcZGO+JYRx86VIAGsAyXguYFKiSl3FywHyeCzHidBWiHMQWJkEnu0CeZfj2aMVEv8gfFXZCI0i+N9dWtU6r561mDHMre66ffDgbyCFrKoNY7UZNNQmEqZSplKmUqZSjXDT9XyOonbE0//q4rQJ5b334xQyp1/vjNAoY5ti92tOPx80JLT75pP09lcpQ7tvgUpRijk7kiZhsJUylTqP1XqrwADAJlpcr3kIV/2AAAAAElFTkSuQmCC" alt="bundle">
                  </div>
                </td>
                <td style="vertical-align: middle;">
                  <a href="{{ url('admin/quickbooks/products-and-services/bundle') }}"><b>Bundle</b></a>
                  <p>A collection of products and/or services that you sell together, for example, a gift basket of fruit, cheese, and wine.</p>
                </td>
              </tr> --}}
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('after_scripts')

@endpush