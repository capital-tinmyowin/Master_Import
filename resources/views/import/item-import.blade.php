 <!-- Item Master Success Records -->
                    <table class="table table-hover item-master-success-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th>Sale Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $data->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $data->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $data->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $data->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->SalePrice ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>













                     <!-- SKU Success Records -->
                    <table class="table table-hover sku-success-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Name</th>
                                <th style="border-right: 1px solid #dee2e6; width: 140px;">JanCD</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: center !important;">{{ $data->Size_Code ?? '-' }}</td>
                                <td style="text-align: center !important;">{{ $data->Color_Code ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Size_Name ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Color_Name ?? '-' }}</td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @php
                                    $janCode = $data->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($data->Quantity ?? 0) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

               <!-- Item Master Error Records -->
                    <table class="table table-hover item-master-error-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th style="border-right: 1px solid #dee2e6;">Sale Price</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errorLogs as $error)
                            <tr>
                                <td style="text-align: center !important;">{{ $error->Item_Code }}</td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $error->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $error->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $error->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $error->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->SalePrice ?? '-' }}
                                </td>
                                <td style="text-align: left !important;" class="item-error-message-cell">
                                    @if(strlen($error->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($error->Error_Msg, 0, 30) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->Error_Msg }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                    <!-- SKU Error Records -->
                    <table class="table table-hover sku-error-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Name</th>
                                <th style="border-right: 1px solid #dee2e6; width: 140px;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Quantity</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errorLogs as $error)
                            <tr>
                                <td style="text-align: center !important;">{{ $error->Item_Code }}</td>
                                <td style="text-align: center !important;">{{ $error->Size_Code ?? '-' }}</td>
                                <td style="text-align: center !important;">{{ $error->Color_Code ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $error->Size_Name ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $error->Color_Name ?? '-' }}</td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @php
                                    $janCode = $error->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($error->Quantity ?? 0) }}
                                </td>
                                <td style="text-align: left !important;" class="error-message-cell">
                                    @if(strlen($error->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($error->Error_Msg, 0, 30) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->Error_Msg }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>



                     <!-- Item Master All Records -->
                    <table class="table table-hover item-master-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Status</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th style="border-right: 1px solid #dee2e6;">Sale Price</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Show success records -->
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Success</span>
                                </td>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $data->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $data->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $data->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $data->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->SalePrice ?? '-' }}
                                </td>
                                <td style="text-align: left !important;" class="item-error-message-cell">
                                    <span class="truncated-error">-</span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Show error records -->
                            @foreach($errorLogs as $error)
                            <tr>
                                <td style="text-align: center !important;">
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i> Error</span>
                                </td>
                                <td style="text-align: center !important;">{{ $error->Item_Code }}</td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $error->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $error->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $error->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $error->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->SalePrice ?? '-' }}
                                </td>
                                <td style="text-align: left !important;" class="item-error-message-cell">
                                    @if(strlen($error->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($error->Error_Msg, 0, 30) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->Error_Msg }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>




                     <!-- SKU All Records -->
                    <table class="table table-hover sku-all-table">
                        <thead>
                            <tr>
                                <th style="width: 80px; text-align: center !important;">Status</th>
                                <th style="width: 100px; text-align: center !important;">Item Code</th>
                                <th style="width: 80px; text-align: center !important;">Size Code</th>
                                <th style="width: 90px; text-align: center !important;">Color Code</th>
                                <th style="width: 120px; text-align: center !important;">Size Name</th>
                                <th style="width: 120px; text-align: center !important;">Color Name</th>
                                <th style="width: 120px; text-align: center !important;">JanCD</th>
                                <th style="width: 80px; text-align: center !important;">Quantity</th>
                                <th style="width: 200px; text-align: center !important;">Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Show success records -->
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Success</span>
                                </td>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: center !important;">{{ $data->Size_Code ?? '-' }}</td>
                                <td style="text-align: center !important;">{{ $data->Color_Code ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Size_Name ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Color_Name ?? '-' }}</td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @if(strlen($data->JanCD ?? '') > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($data->JanCD ?? '', ENT_QUOTES) }}">
                                        {{ substr($data->JanCD ?? '-', 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $data->JanCD ?? '-' }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($data->Quantity ?? 0) }}
                                </td>
                                <td style="text-align: left !important;" class="error-message-cell">
                                    <span class="truncated-error">-</span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Show error records -->
                            @foreach($errorLogs as $error)
                            <tr>
                                <td style="text-align: center !important;">
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i> Error</span>
                                </td>
                                <td style="text-align: center !important;">{{ $error->Item_Code }}</td>
                                <td style="text-align: center !important;">{{ $error->Size_Code ?? '-' }}</td>
                                <td style="text-align: center !important;">{{ $error->Color_Code ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $error->Size_Name ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $error->Color_Name ?? '-' }}</td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @if(strlen($error->JanCD ?? '') > 12)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->JanCD ?? '', ENT_QUOTES) }}">
                                        {{ substr($error->JanCD ?? '-', 0, 12) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->JanCD ?? '-' }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($error->Quantity ?? 0) }}
                                </td>
                                <td style="text-align: left !important;" class="error-message-cell">
                                    @if(strlen($error->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($error->Error_Msg, 0, 30) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->Error_Msg }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>