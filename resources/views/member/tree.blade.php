@extends('member.layout')

@section('title', 'Binary Tree')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Binary Tree View</h4>
                </div>
                <div class="card-body">
                    <div class="tree-container">
                        @if($tree)
                        @include('member.tree_node', ['node' => $tree, 'level' => 0])
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tree data found for your account.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Details Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="memberDetails">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hover Tooltip -->
<div id="hoverTooltip" class="hover-tooltip">
    <div id="tooltipContent"></div>
</div>

<style>
    /*.tree-container {*/
    /*    display: flex;*/
    /*    justify-content: center;*/
    /*    align-items: flex-start;*/
    /*    min-height: 500px;*/
    /*    overflow-x: auto;*/
    /*    padding: 20px;*/
    /*}*/
      .tree-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 500px;
        overflow-x: auto;
        padding: 20px;
    }
    @media (max-width: 768px) {
    .tree-container {
        justify-content: flex-start;
    }
}

    .tree-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 10px;
        position: relative;
    }

    .node-card {
        background: #fff;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 120px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .node-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        border-color: #007bff;
    }

    .node-card.active {
        border-color: #28a745;
        background: #f8fff9;
    }

    .node-card.inactive {
        border-color: #dc3545;
        background: #fff8f8;
        opacity: 0.7;
    }

    .node-card.empty {
        border-style: dashed;
        border-color: #ccc;
        background: #f9f9f9;
        opacity: 0.6;
    }

    .node-name {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 5px;
        color: #333;
    }

    .node-id {
        font-size: 10px;
        color: #666;
    }

    .children-container {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        position: relative;
    }

    .children-container::before {
        content: '';
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 15px;
        background: #ddd;
    }

    .children-container::after {
        content: '';
        position: absolute;
        top: -15px;
        left: 25%;
        width: 50%;
        height: 2px;
        background: #ddd;
    }

    .level-1 .children-container {
        min-width: 300px;
    }

    .level-2 .children-container {
        min-width: 150px;
    }

    /* Connecting lines */
    .tree-node.has-children>.children-container>.tree-node::before {
        content: '';
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 15px;
        background: #ddd;
    }

    /* Hover tooltip styles */
    .hover-tooltip {
        position: fixed;
        background: rgba(0, 0, 0, 0.95);
        color: white;
        padding: 15px;
        border-radius: 8px;
        font-size: 12px;
        z-index: 10000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        max-width: 300px;
        min-width: 250px;
    }

    .hover-tooltip.show {
        opacity: 1;
    }

    .tooltip-header {
        font-weight: bold;
        margin-bottom: 8px;
        padding-bottom: 5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .tooltip-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .tooltip-label {
        font-weight: 500;
        opacity: 0.8;
    }

    .tooltip-value {
        font-weight: bold;
    }

    .business-section {
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<script>
    let hoverTimeout;
    let currentTooltip = null;

    function initNodeEvents() {
        document.querySelectorAll('.node-card:not(.empty)').forEach(function (card) {
            const memid = card.dataset.memid;

            // Click event for navigation
            card.addEventListener('click', function (e) {
                e.stopPropagation();
                
                // Clear any hover tooltips
                hideTooltip();
                
                // Navigate to this member's tree
                window.location.href = `/member/tree?root=${memid}`;
            });

            // Right-click for member details modal
            // card.addEventListener('contextmenu', function (e) {
            //     e.preventDefault();
            //     e.stopPropagation();
            //     hideTooltip();
            //     showMemberDetails(memid);
            // });

            // Hover events for tooltip
            card.addEventListener('mouseenter', function (e) {
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    showHoverTooltip(memid, e);
                }, 500); // 500ms delay before showing tooltip
            });

            card.addEventListener('mouseleave', function (e) {
                clearTimeout(hoverTimeout);
                hideTooltip();
            });

            // Prevent tooltip from interfering with clicks
            card.addEventListener('mousedown', function (e) {
                clearTimeout(hoverTimeout);
                hideTooltip();
            });
        });
    }

    function showHoverTooltip(memid, event) {
        const tooltip = document.getElementById('hoverTooltip');
        const tooltipContent = document.getElementById('tooltipContent');
        
        // Show loading state
        tooltipContent.innerHTML = '<div class="text-center">Loading...</div>';
        tooltip.style.opacity = '1';
        
        // Position tooltip near cursor
        updateTooltipPosition(event);
        
        // Fetch member details for tooltip
        fetch(`/member/details/${memid}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    tooltipContent.innerHTML = `<div class="text-danger">${data.error}</div>`;
                    return;
                }

                tooltipContent.innerHTML = `
                    <div class="tooltip-header">${data.name} (${data.memid})</div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Status:</span>
                        <span class="tooltip-value ${data.status === 'Active' ? 'text-success' : 'text-danger'}">${data.status || 'N/A'}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Sponsor:</span>
                        <span class="tooltip-value">${data.sponsor}</span>
                    </div>
                      <div class="tooltip-row">
                        <span class="tooltip-label">joining packag:</span>
                        <span class="tooltip-value">${data.joining_packag || 0}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Join Date:</span>
                        <span class="tooltip-value">${data.join_date}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Active Direct:</span>
                        <span class="tooltip-value">${data.active_direct} Members</span>
                    </div>
                    <div class="business-section">
                        <div class="tooltip-row">
                            <span class="tooltip-label">Left Business:</span>
                            <span class="tooltip-value text-info">₹${data.left_business || 0}</span>
                        </div>
                        <div class="tooltip-row">
                            <span class="tooltip-label">Right Business:</span>
                            <span class="tooltip-value text-info">₹${data.right_business || 0}</span>
                        </div>
                    
                    </div>
                `;
                
                tooltip.classList.add('show');
                currentTooltip = tooltip;
            })
            .catch(error => {
                console.error('Error:', error);
                tooltipContent.innerHTML = '<div class="text-danger">Error loading details</div>';
            });
    }

    function updateTooltipPosition(event) {
        const tooltip = document.getElementById('hoverTooltip');
        const rect = tooltip.getBoundingClientRect();
        
        let x = event.clientX + 15;
        let y = event.clientY - 10;
        
        // Adjust if tooltip would go off screen
        if (x + rect.width > window.innerWidth) {
            x = event.clientX - rect.width - 15;
        }
        
        if (y + rect.height > window.innerHeight) {
            y = event.clientY - rect.height - 10;
        }
        
        tooltip.style.left = x + 'px';
        tooltip.style.top = y + 'px';
    }

    function hideTooltip() {
        const tooltip = document.getElementById('hoverTooltip');
        tooltip.classList.remove('show');
        tooltip.style.opacity = '0';
        currentTooltip = null;
    }

    function showMemberDetails(memid) {
        const modal = new bootstrap.Modal(document.getElementById('memberModal'));
        const modalBody = document.getElementById('memberDetails');

        modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        `;

        modal.show();

        fetch(`/member/details/${memid}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    modalBody.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                modalBody.innerHTML = `
                <div class="member-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label><strong>Login Name:</strong></label>
                                <p>${data.name} (${data.memid})</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Sponsor By:</strong></label>
                                <p>${data.sponsor}</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Upline ID:</strong></label>
                                <p>${data.upline}</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Joining Date:</strong></label>
                                <p>${data.join_date}</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Activation Date:</strong></label>
                                <p>${data.activation_date}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label><strong>Active Direct:</strong></label>
                                <p>${data.active_direct} Members</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Inactive Direct:</strong></label>
                                <p>${data.inactive_direct} Members</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Left Business:</strong></label>
                                <p class="text-info"><strong>₹${data.left_business || 0}</strong></p>
                            </div>
                            <div class="info-group">
                                <label><strong>Right Business:</strong></label>
                                <p class="text-info"><strong>₹${data.right_business || 0}</strong></p>
                            </div>
                            <div class="info-group">
                                <label><strong>Total Business:</strong></label>
                                <p class="text-warning"><strong>₹${(parseFloat(data.left_business || 0) + parseFloat(data.right_business || 0))}</strong></p>
                            </div>
                            <div class="info-group">
                                <label><strong>Active Network:</strong></label>
                                <p><strong>Left:</strong> ${data.active_network_left} Members | <strong>Right:</strong> ${data.active_network_right} Members</p>
                            </div>
                            <div class="info-group">
                                <label><strong>In-Active Network:</strong></label>
                                <p><strong>Left:</strong> ${data.inactive_network_left} Members | <strong>Right:</strong> ${data.inactive_network_right} Members</p>
                            </div>
                            <div class="info-group">
                                <label><strong>Total Team:</strong></label>
                                <p>${data.total_team} Members</p>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = '<div class="alert alert-danger">Error loading member details</div>';
            });
    }

    // Hide tooltip when clicking elsewhere
    document.addEventListener('click', function(e) {
        hideTooltip();
    });

    // Handle mouse movement for tooltip positioning
    document.addEventListener('mousemove', function(e) {
        if (currentTooltip && currentTooltip.classList.contains('show')) {
            updateTooltipPosition(e);
        }
    });

    document.addEventListener('DOMContentLoaded', initNodeEvents);
</script>
@endsection