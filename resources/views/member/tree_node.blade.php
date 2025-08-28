{{-- resources/views/member/tree_node.blade.php --}}
<div class="tree-node level-{{ $level }} {{ ($node['left'] || $node['right']) ? 'has-children' : '' }}">

    <div class="node-card {{ $node['status'] ? 'active' : 'inactive' }}"
        data-memid="{{ $node['memid'] }}"
        ondblclick="loadSubTree('{{ $node['memid'] }}')">

        {{-- Status-based icon --}}
        <div class="node-icon mb-1">
            @if ($node['status'])
                <i class="fas fa-user-circle" style="color: green; font-size: 22px;" title="Active User"></i>
            @else
                <i class="fas fa-user-circle" style="color: red; font-size: 22px;" title="Inactive User"></i>
            @endif
        </div>

        <div class="node-name">{{ $node['name'] }}</div>
        <div class="node-id">{{ $node['memid'] }}</div>
    </div>

    {{-- Only render children if below level 2 --}}
    @if ($level < 2)
        <div class="children-container">
            @php
                $memberid = auth()->user()->show_mem_id;
                $rightReferralLink = route('member.register', [
                    'memberid' => $node['memid'],
                    'position' => 'left',
                    'sponsor'  => $memberid
                ]);

                $leftReferralLink = route('member.register', [
                    'memberid' => $node['memid'],
                    'position' => 'right',
                    'sponsor'  => $memberid
                ]);
            @endphp

            {{-- Left Child --}}
            @if ($node['left'])
                @include('member.tree_node', ['node' => $node['left'], 'level' => $level + 1])
            @else
                <div class="tree-node empty-node level-{{ $level + 1 }}">
                    <a href="{{ $rightReferralLink }}" class="node-card empty" style="text-decoration: none;" target="_blank">
                        <div class="node-icon mb-1">
                            <i class="fas fa-user-circle text-muted" style="font-size: 22px;" title="Empty Position"></i>
                        </div>
                        <div class="node-name">Empty</div>
                        <div class="node-id">-</div>
                    </a>
                </div>
            @endif

            {{-- Right Child --}}
            @if ($node['right'])
                @include('member.tree_node', ['node' => $node['right'], 'level' => $level + 1])
            @else
                <div class="tree-node empty-node level-{{ $level + 1 }}">
                    <a href="{{ $leftReferralLink }}" class="node-card empty" style="text-decoration: none;" target="_blank">
                        <div class="node-icon mb-1">
                            <i class="fas fa-user-circle text-muted" style="font-size: 22px;" title="Empty Position"></i>
                        </div>
                        <div class="node-name">Empty</div>
                        <div class="node-id">-</div>
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>
