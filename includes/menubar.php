<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            
            <li class=""></li>
                <?php if(strtolower($_SESSION['userType']) != 'sales executive' && strtolower($_SESSION['userType']) != "shop executive") { ?>
                <li><a href="#"  onclick="advanceSearch('stockSerach')"><i class="fa fa-search"></i> <span> Advance Stock Search </span></a></li>
                 <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Inventory</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="manageItem-view.php"><i class="fa fa-bars"></i> <span>Product List</span></a></li>
                        <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == "admin sales") { 
                            if(strtolower($_SESSION['userType']) == "admin sales") { ?>
                                <li><a href="manageWareHouseTransfer-view.php"><i class="fa fa-bars"></i> <span>Warehouse Transfer</span></a></li>
                        <?php 
                            }else{
                        ?>
                                <li><a href="manageWareHouseTransfer-view.php"><i class="fa fa-bars"></i> <span>Warehouse Transfer</span></a></li>
                                <li><a href="manageDamageProducts-view.php"><i class="fa fa-bars"></i> <span>Damage Products</span></a></li>
                        <?php 
                            }
                        } ?>
    				</ul>
                </li>
                <?php } ?>
            <?php if(strtolower($_SESSION['userType']) == "sales executive") { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Sales Executive</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="orderPanel.php?id=0"><i class="fa fa-bars"></i> <span>Order Panel</span></a></li>
                    <li><a href="orderList.php?page=Pending"><i class="fa fa-bars"></i> <span>Pending Order</span></a></li>
                    <li><a href="orderList.php?page=Checked"><i class="fa fa-bars"></i> <span>Checked Order</span></a></li>
                    <li><a href="orderProcessList.php"><i class="fa fa-bars"></i> <span>Processing Order</span></a></li>
                    <li><a href="orderList.php?page=Completed"><i class="fa fa-bars"></i> <span>complete Order</span></a></li>
                    
				</ul>
            </li>
            <?php } ?>
            <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin') { ?>
            <!--li class="treeview">
                <a href="#">
                    <i class="fa fa-deaf"></i>
                    <span>Sales Order</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="orderList.php?page=Pending"><i class="fa fa-bars"></i> <span>Pending Order</span></a></li>
                    <li><a href="orderList.php?page=Checked"><i class="fa fa-bars"></i> <span>Checked Order</span></a></li>
                    <li><a href="orderProcessList.php"><i class="fa fa-bars"></i> <span>Process Order</span></a></li>
                    <li><a href="orderList.php?page=Completed"><i class="fa fa-bars"></i> <span>Complete Order</span></a></li>
                </ul>
            </li-->
            <?php } ?>
            <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin') { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cart-plus"></i>
                    <span>Purchase</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="purchaseLocal-view.php"><i class="fa fa-bars"></i> <span>Purchase</span></a></li>
                    <li><a href="purchaseLocalViewreturn.php"><i class="fa fa-bars"></i> <span>Purchase Return</span></a></li>
                    <li><a href="purchaseForeign-view.php"><i class="fa fa-bars"></i> <span>Import</span></a></li>
				</ul>
            </li>
            <?php  } ?>
            <?php if(strtolower($_SESSION['userType']) != 'sales executive' && strtolower($_SESSION['userType']) != "shop executive") { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Sales</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="walkInSaleView.php"><i class="fa fa-bars"></i> <span>Invoice </span></a></li>
                    <li><a href="saleReturnView.php?salesType=WalkinSale"><i class="fa fa-bars"></i> <span>Invoice Sale Return</span></a></li>
                    <li><a href="wholeSaleView.php"><i class="fa fa-bars"></i> <span>Party Invoice</span></a></li>
                    <li><a href="saleReturnView.php?salesType=PartySale"><i class="fa fa-bars"></i> <span>Party Invoice Return</span></a></li>
                    <li><a href="temporarySaleView.php"><i class="fa fa-bars"></i> <span>Sales Challan</span></a></li>
                    <li><a href="finalSaleView.php"><i class="fa fa-bars"></i> <span>Final Invoice</span></a></li>
                    <li><a href="saleReturnView.php?salesType=FS"><i class="fa fa-bars"></i> <span>Final Invoice Return</span></a></li>
                    <li><a href="saleReturnView.php?salesType=TS"><i class="fa fa-bars"></i> <span>Sales Challan Return</span></a></li>
                     <!--li><a href="repaireCenter-view.php"><i class="fa fa-recycle"></i> <span>Repair Center</span></a></li-->
                </ul>
            </li>
            <?php } ?>
            <?php if(strtolower($_SESSION['userType']) != 'sales executive' && strtolower($_SESSION['userType']) != "shop executive") { ?>
            <!--li class="treeview">
                <a href="#">
                    <i class="fa fa-clone"></i>
                    <span>Challan Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
					<li><a href="challanView.php"><i class="fa fa-bars"></i> <span>Challan</span></a></li>
				</ul>
			</li-->
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-address-book-o"></i>
                    <span>Voucher Entry</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="manageVoucher.php?voucherType=paymentReceived"><i class="fa fa-bars"></i> <span>Received Voucher</span></a></li>
                    <li><a href="manageVoucher.php?voucherType=payment"><i class="fa fa-bars"></i> <span>Payment Voucher</span></a></li>
                    <li><a href="manageVoucher.php?voucherType=discount"><i class="fa fa-bars"></i> <span>Discount Voucher</span></a></li>
                    <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin') { ?>
                        <li><a href="manageVoucher.php?voucherType=adjustment"><i class="fa fa-bars"></i> <span>Return Voucher</span></a></li>
                        <li><a href="paymentReceiveVoucher.php"><i class="fa fa-bars"></i> <span>Bulk Cheque Voucher</span></a></li>
                    <?php } ?>  
                     <?php if(strtolower($_SESSION['userType']) == 'super admin') { ?>
                        
                    <?php } ?>
                    
                </ul>
            </li>
            <?php } ?>
            <?php if(strtolower($_SESSION['userType']) != 'sales executive' && strtolower($_SESSION['userType']) != "shop executive") { ?>
			<li class="treeview">
                <a href="#">
                    <i class="fa fa-handshake-o"></i>
                    <span>CRM</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="manageWalkInCustomer-view.php"><i class="fa fa-bars"></i> <span>Invoice Customer List</span></a></li>
                    <li><a href="manageCustomerSupplier-view.php?page=Customers"><i class="fa fa-bars"></i> <span>Customers List</span></a></li>
                    <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin') { ?>
                        <li><a href="manageCustomerSupplier-view.php?page=Suppliers"><i class="fa fa-bars"></i> <span>Suppliers List</span></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php  if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin') { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-gears"></i>
                        <span>Settings</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="manage-view.php?page=Unit"><i class="fa fa-bars"></i> <span>Unit</span></a></li>
                        <li><a href="manage-view.php?page=Brand"><i class="fa fa-bars"></i> <span>Brands</span></a></li>
                        <li><a href="manage-view.php?page=Warehouse"><i class="fa fa-bars"></i> <span>Warehouse</span></a></li>
                        <li><a href="manage-view.php?page=Category"><i class="fa fa-bars"></i> <span>Product Category</span></a></li>
                        <li><a href="manage-view.php?page=PaymentMethod"><i class="fa fa-bars"></i> <span>Payment Method</span></a></li>
                        <li><a href="manageTransport-view.php"><i class="fa fa-bars"></i> <span>Manage Transport</span></a></li>
                   
                        <?php if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == "admin support plus") { ?>
                            <li><a href="manageBankInfo-view.php"><i class="fa fa-bars"></i> <span>Bank Account Info</span></a></li>
                            <!--li><a href="discountOffer-view.php"><i class="fa fa-bars"></i> <span>Discount Offer</span></a></li>
                            <li><a href="discountOfferAlert-view.php"><i class="fa fa-bars"></i> <span>Discount Alert List</span></a></li-->
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?> 
            
            <?php if(strtolower($_SESSION['userType']) == 'super admin') { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>User Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="manageUser-view.php"><i class="fa fa-bars"></i> <span>Manage User</span></a></li>
                    <li><a href="#ChangePassword" data-toggle="modal"><i class="fa fa-bars"></i> <span>Change Password</span></a></li>
                    <li><a href="manage-view.php?page=User Type"><i class="fa fa-bars"></i> <span>User Type</span></a></li>
            
                </ul>
            </li>
            <?php } ?> 
		    <?php if(strtolower($_SESSION['userType']) != 'sales executive' && strtolower($_SESSION['userType']) != "shop executive") { ?>
		    <!--li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Incentive Panel</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="incentive.php"><i class="fa fa-bars"></i> <span>Crete Incentive</span></a></li>
            
                </ul>
            </li-->
		    
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-clone"></i>
                    <span> Reports </span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    
                    <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin'){ ?>
                        <li><a href="dailyCashSalespdf-view.php"><i class="fa fa-file-pdf-o"></i> <span> Day Wise Cash ledger </span></a></li>
                        <li><a href="dailyCashSalesLedger-view.php"><i class="fa fa-file-pdf-o"></i> <span> Method Wise Received Balance</span></a></li>
                        <li><a href="dateWiseCash-view.php"><i class="fa fa-file-pdf-o"></i> <span> Date wise Received Balance </span></a></li>
                        <li><a href="dailySalesHistorypdf-view.php"><i class="fa fa-file-pdf-o"></i> <span> Day Wise Sales History </span></a></li>
                        <li><a href="duePartyInformation-view.php"><i class="fa fa-file-pdf-o"></i> <span> No Payment Due</span></a></li>
                        <li><a href="unsoldProductInformation-view.php"><i class="fa fa-file-pdf-o"></i> <span> Unsold Product Information</span></a></li>
                    <?php } ?>
                        <?php if(strtolower($_SESSION['userType']) == 'super admin'){ ?>
                            <!--li><a href="stockReportsTotalpdfPrint.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span>Total Product Stock </span></a></li-->
                            <li><a href="referenceSales-view.php"><i class="fa fa-file-pdf-o"></i> <span> Reference By Sales</span></a></li>
                            
                            <li><a href="partyPayablePdfPrint.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span> Party Payable</span></a></li>
                            <li><a href="partyReceivablePdfPrint.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span> Party Receivable</span></a></li>
                            <li><a href="damageProductsPdfPrint.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span> Damage Products</span></a></li>
                        <?php } ?>
                        <?php if($_SESSION['user'] == '22' || $_SESSION['user'] == '2' || $_SESSION['user'] == '3'){ ?>
                        <li><a href="financialPosition-view.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span> Finansial Position</span></a></li>
                        <?php } ?>
                    <li><a href="stockReports-view.php"><i class="fa fa-file-pdf-o"></i> <span>Item Stock Ledger</span></a></li>
                    <li><a href="partyLedgerpdf-view.php"><i class="fa fa-file-pdf-o"></i> <span> Party Ledger</span></a></li>
                    <li><a href="walkinSalesLedgerpdf-view.php"><i class="fa fa-file-pdf-o"></i> <span> Walkin Sales Ledger</span></a></li>
                    <!--li><a href="TsSalesLedgerpdf-view.php" target="_blank"><i class="fa fa-file-pdf-o"></i> <span> TS Ledger</span></a></li -->
                    <li><a href="tsSalesLedger-view.php"><i class="fa fa-file-pdf-o"></i> <span> TS Ledger Running..</span></a></li>
                    
                    
                </ul>
                
            </li>
            <?php } ?>
            
            <?php if(strtolower($_SESSION['userType']) == 'super admin') { ?>
            <!--li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Catalogue Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="managePrintBook-view.php"><i class="fa fa-bars"></i> <span>Catalogue Name View</span></a></li>
                    <li><a href="managePrintBookCategory-view.php"><i class="fa fa-bars"></i> <span>Catalogue Print View</span></a></li>
                    <li><a href="managePrintBookCategory-pdf-view.php"><i class="fa fa-bars"></i> <span>Catelogue PDF View</span></a></li>
                    <!--li><a href="managePrintBookProduct-view.php"><i class="fa fa-bars"></i> <span>PrintBook Product View</span></a></li>
                    <li><a href="managePrintBookSpecDis-view.php"><i class="fa fa-bars"></i> <span>PrintBook Spec View</span></a></li>
                    <li><a href="dataGridView.php"><i class="fa fa-bars"></i> <span>Data Grid View</span></a></li>
                    <li><a href="dataGridViewTest.php"><i class="fa fa-bars"></i> <span>Data Grid View Test</span></a></li>
                </ul>
            </li-->
            <?php } ?>
            <?php if(strtolower($_SESSION['userType']) != 'super admin') { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Catalogue Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="managePrintBook-view.php"><i class="fa fa-bars"></i> <span>Catalogue Name View</span></a></li>
                    <li><a href="managePrintBookCategory-view.php"><i class="fa fa-bars"></i> <span>Catalogue Print View</span></a></li>
                    <li><a href="managePrintBookCategory-pdf-view.php"><i class="fa fa-bars"></i> <span>Catelogue PDF View</span></a></li>
                    <!--li><a href="managePrintBookProduct-view.php"><i class="fa fa-bars"></i> <span>PrintBook Product View</span></a></li>
                    <li><a href="managePrintBookSpecDis-view.php"><i class="fa fa-bars"></i> <span>PrintBook Spec View</span></a></li>
                    <li><a href="dataGridView.php"><i class="fa fa-bars"></i> <span>Data Grid View</span></a></li>
                    <li><a href="dataGridViewTest.php"><i class="fa fa-bars"></i> <span>Data Grid View Test</span></a></li-->
                </ul>
            </li>
            <?php } ?>
            <!--li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Calendar Activities</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="calendar-view.php"><i class="fa fa-calendar"></i> <span>Full Calendar</span></a></li>
                    <li><a href="onday-view.php"><i class="fa fa-calendar"></i> <span>Working Calendar</span></a></li>
                    <li><a href="offday-view.php"><i class="fa fa-calendar"></i> <span>Offday Calendar</span></a></li>
                    <li><a href="holiday-view.php"><i class="fa fa-calendar"></i> <span>Holiday Calendar</span></a></li>
                </ul>
            </li-->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<div id="loading" style="display:none;">
    <img id="loading-image" src="images/loader.gif" alt="Loading..."  />
</div>