<?php
/*
 *
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
*/
class LeaveRequestService extends BaseService {

    private $leaveRequestDao ;
    private $leaveTypeService;
    private $leaveEntitlementService;
    private $leavePeriodService;

    private $leaveNotificationService;

    const LEAVE_CHANGE_TYPE_LEAVE = 'change_leave';
    const LEAVE_CHANGE_TYPE_LEAVE_REQUEST = 'change_leave_request';

    /**
     *
     * @return LeaveRequestDao
     */
    public function getLeaveRequestDao() {
        return $this->leaveRequestDao;
    }

    /**
     *
     * @param LeaveRequestDao $leaveRequestDao
     * @return void
     */
    public function setLeaveRequestDao( LeaveRequestDao $leaveRequestDao) {
        $this->leaveRequestDao = $leaveRequestDao;
    }

    /**
     *
     * @return LeaveNotificationService
     */
    public function getLeaveNotificationService() {
        $this->leaveNotificationService = new LeaveNotificationService();
        return $this->leaveNotificationService;
    }

    /**
     *
     * @param LeaveRequest $leaveRequest
     * @param Leave $leave
     * @return boolean
     */
    public function saveLeaveRequest( LeaveRequest $leaveRequest , $leaveList) {

        $this->getLeaveRequestDao()->saveLeaveRequest( $leaveRequest, $leaveList);

        return true ;

    }

    /**
     * @return LeaveEntitlementService
     */
    public function getLeaveEntitlementService() {
        if(is_null($this->leaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
            $this->leaveEntitlementService->setLeaveEntitlementDao(new LeaveEntitlementDao());
        }
        return $this->leaveEntitlementService;
    }

    /**
     * @return LeaveTypeService
     */
    public function getLeaveTypeService() {
        if(is_null($this->leaveTypeService)) {
            $this->leaveTypeService	=	new LeaveTypeService();
            $this->leaveTypeService->setLeaveTypeDao(new LeaveTypeDao());
        }
        return $this->leaveTypeService;
    }

    /**
     * Sets LeaveEntitlementService
     * @param LeaveEntitlementService $leaveEntitlementService
     */
    public function setLeaveEntitlementService(LeaveEntitlementService $leaveEntitlementService) {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    /**
     * Sets LeaveTypeService
     * @param LeaveTypeService $leaveTypeService
     */
    public function setLeaveTypeService(LeaveTypeService $leaveTypeService) {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Returns LeavePeriodService
     * @return LeavePeriodService
     */
    public function getLeavePeriodService() {
        if(is_null($this->leavePeriodService)) {
            $this->leavePeriodService = new LeavePeriodService();
            $this->leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
        }
        return $this->leavePeriodService;
    }

    /**
     * Sets LeavePeriodService
     * @param LeavePeriodService $leavePeriodService
     */
    public function setLeavePeriodService(LeavePeriodService $leavePeriodService) {
        $this->leavePeriodService = $leavePeriodService;
    }

    /**
     *
     * @param Employee $employee
     * @return LeaveType Collection
     */
    public function getEmployeeAllowedToApplyLeaveTypes(Employee $employee) {

        try {
            $leavePeriodService = $this->getLeavePeriodService();
            $leavePeriod = $leavePeriodService->getCurrentLeavePeriod();

            $leaveEntitlementService    = $this->getLeaveEntitlementService();
            $leaveTypeService           = $this->getLeaveTypeService();

            $leaveTypes     = $leaveTypeService->getLeaveTypeList();
            $leaveTypeList  = array();

            foreach($leaveTypes as $leaveType) {
                $entitlementDays = $leaveEntitlementService->getLeaveBalance($employee->getEmpNumber(), $leaveType->getLeaveTypeId(),$leavePeriod->getLeavePeriodId());

                if($entitlementDays > 0) {
                    array_push($leaveTypeList, $leaveType);
                }
            }
            return $leaveTypeList;
        } catch(Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param date $leaveStartDate
     * @param date $leaveEndDate
     * @param int $empId
     * @return Leave List
     */
    public function getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId) {

        return $this->leaveRequestDao->getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId);

    }

    /**
     *
     * @param LeaveType $leaveType
     * @return boolean
     */
    /*public function isApplyToMoreThanCurrent(LeaveType $leaveType){
		try{
			$leaveRuleEligibilityProcessor	=	new LeaveRuleEligibilityProcessor();
			return $leaveRuleEligibilityProcessor->allowApplyToMoreThanCurrent($leaveType);

		}catch( Exception $e){
			throw new LeaveServiceException($e->getMessage());
		}
	}*/

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfLeave($empId, $leaveTypeId) {

        return $this->leaveRequestDao->getNumOfLeave($empId, $leaveTypeId);

    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfAvaliableLeave($empId, $leaveTypeId) {

        return $this->leaveRequestDao->getNumOfAvaliableLeave($empId, $leaveTypeId);

    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return bool
     */
    public function isEmployeeHavingLeaveBalance( $empId, $leaveTypeId ,$leaveRequest,$applyDays) {
        try {
            $leaveEntitlementService = $this->getLeaveEntitlementService();
            $entitledDays	=	$leaveEntitlementService->getEmployeeLeaveEntitlementDays($empId, $leaveTypeId,$leaveRequest->getLeavePeriodId());
            $leaveDays		=	$this->leaveRequestDao->getNumOfAvaliableLeave($empId, $leaveTypeId);

            $leaveEntitlement = $leaveEntitlementService->readEmployeeLeaveEntitlement($empId, $leaveTypeId, $leaveRequest->getLeavePeriodId());
            $leaveBoughtForward = 0;
            if($leaveEntitlement instanceof EmployeeLeaveEntitlement) {
                $leaveBoughtForward = $leaveEntitlement->getLeaveBroughtForward();
            }

            $leaveBalance = $leaveEntitlementService->getLeaveBalance(
                    $empId, $leaveTypeId,
                    $leaveRequest->getLeavePeriodId());

            $entitledDays += $leaveBoughtForward;

            if($entitledDays == 0)
                throw new Exception('Leave Quota not allocated',102);

            //this is for border period leave apply - days splitting
            $leavePeriodService = $this->getLeavePeriodService();

            //this would either create or returns the next leave period
            $currentLeavePeriod     = $leavePeriodService->getLeavePeriod(strtotime($leaveRequest->getDateApplied()));
            $leaveAppliedEndDateTimeStamp = strtotime("+" . $applyDays . " day", strtotime($leaveRequest->getDateApplied()));
            $nextLeavePeriod        = $leavePeriodService->createNextLeavePeriod(date("Y-m-d", $leaveAppliedEndDateTimeStamp));
            $currentPeriodStartDate = explode("-", $currentLeavePeriod->getStartDate());
            $nextYearLeaveBalance   = 0;

            if($nextLeavePeriod instanceof LeavePeriod) {
                $nextYearLeaveBalance = $leaveEntitlementService->getLeaveBalance(
                        $empId, $leaveTypeId,
                        $nextLeavePeriod->getLeavePeriodId());
                //this is to notify users are applying to the same leave period
                $nextPeriodStartDate    = explode("-", $nextLeavePeriod->getStartDate());
                if($nextPeriodStartDate[0] == $currentPeriodStartDate[0]) {
                    $nextLeavePeriod        = null;
                    $nextYearLeaveBalance   = 0;
                }
            }

            //this is only applicable if user applies leave during current leave period
            if(strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($currentLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {
                if($leaveBalance < $applyDays) {
                    throw new Exception('leave balance exceed',102);
                }
            }

            //this is to verify whether leave applied within border period
            if($nextLeavePeriod instanceof LeavePeriod && strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($nextLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {

                $endDateTimeStamp = strtotime($leavePeriodService->getCurrentLeavePeriod()->getEndDate());
                $borderDays = date("d", ($endDateTimeStamp - strtotime($leaveRequest->getDateApplied())));
                if($borderDays > $leaveBalance || $nextYearLeaveBalance < ($applyDays - $borderDays)) {
                    throw new Exception("leave balance exceed", 102);
                }
            }

            return true ;

        }catch( Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function searchLeaveRequests($searchParameters, $page = 1) {

        return $this->leaveRequestDao->searchLeaveRequests($searchParameters, $page);

    }

    /**
     * Get Leave Request Status
     * @param $day
     * @return unknown_type
     */
    public function getLeaveRequestStatus( $day ) {
        try {
            $holidayService	=	new HolidayService();
            $holidayService->setHolidayDao(new HolidayDao());
            $holiday		=	$holidayService->readHolidayByDate($day);
            if ($holiday != null) {
                return Leave::LEAVE_STATUS_LEAVE_HOLIDAY;
            }

            return Leave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL;

        } catch (Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param int $leaveRequestId
     * @return array
     */
    public function searchLeave($leaveRequestId) {

        return $this->leaveRequestDao->fetchLeave($leaveRequestId);

    }

    /**
     *
     * @param int $leaveId
     * @return array
     */
    public function readLeave($leaveId) {

        return $this->leaveRequestDao->readLeave($leaveId);

    }

    public function saveLeave(Leave $leave) {
        return $this->leaveRequestDao->saveLeave($leave);
    }

    /**
     * @param int $leaveRequestId
     */
    public function fetchLeaveRequest($leaveRequestId) {

        return $this->leaveRequestDao->fetchLeaveRequest($leaveRequestId);

    }

    /**
     * Modify Over lap leaves
     * @param LeaveRequest $leaveRequest
     * @param $leaveList
     * @return unknown_type
     */
    public function modifyOverlapLeaveRequest(LeaveRequest $leaveRequest , $leaveList ) {

        return $this->leaveRequestDao->modifyOverlapLeaveRequest($leaveRequest , $leaveList);

    }

    /**
     *
     * @param LeavePeriod $leavePeriod
     * @return boolean
     */
    public function adjustLeavePeriodOverlapLeaves(LeavePeriod $leavePeriod) {

        $overlapleaveList =	$this->leaveRequestDao->getLeavePeriodOverlapLeaves($leavePeriod);

        if (count($overlapleaveList) > 0) {

            foreach($overlapleaveList as $leave) {

                $leaveRequest	=	$leave->getLeaveRequest();
                $leaveList		=	$this->leaveRequestDao->fetchLeave($leaveRequest->getLeaveRequestId());
                $this->leaveRequestDao->modifyOverlapLeaveRequest($leaveRequest,$leaveList,$leavePeriod);

            }

        }

    }

    /**
     *
     * @param array $changes
     * @param string $changeType
     * @return boolean
     */
    public function changeLeaveStatus($changes, $changeType, $changeComments = null, $changedByUserType = null, $changedUserId = null) {
        if(is_array($changes)) {
            $approvalIds = array_keys(array_filter($changes, array($this, '_filterApprovals')));
            $rejectionIds = array_keys(array_filter($changes, array($this, '_filterRejections')));
            $cancellationIds = array_keys(array_filter($changes, array($this, '_filterCancellations')));


            if ($changeType == 'change_leave_request') {
                foreach ($approvalIds as $leaveRequestId) {
                    $approvals = $this->searchLeave($leaveRequestId);
                    $this->_approveLeave($approvals, $changeComments[$leaveRequestId]);
                    $leaveApprovalMailer = new LeaveApprovalMailer($approvals, $changedByUserType, $changedUserId, 'request');
                    $leaveApprovalMailer->send();
                }

                foreach ($rejectionIds as $leaveRequestId) {
                    $rejections = $this->searchLeave($leaveRequestId);
                    $this->_rejectLeave($rejections, $changeComments[$leaveRequestId]);
                    $leaveRejectionMailer = new LeaveRejectionMailer($rejections, $changedByUserType, $changedUserId, 'request');
                    $leaveRejectionMailer->send();
                }

                foreach ($cancellationIds as $leaveRequestId) {
                    $cancellations = $this->searchLeave($leaveRequestId);
                    $this->_cancelLeave($cancellations, $changedByUserType);
                    
                    if ($changedByUserType == Users::USER_TYPE_EMPLOYEE) {
                        $leaveCancellationMailer = new LeaveEmployeeCancellationMailer($cancellations, $changedByUserType, $changedUserId, 'request');
                    } else {
                        $leaveCancellationMailer = new LeaveCancellationMailer($cancellations, $changedByUserType, $changedUserId, 'request');
                    }
                    
                    $leaveCancellationMailer->send();
                }

            } elseif ($changeType == 'change_leave') {

                $approvals = array();
                foreach ($approvalIds as $leaveId) {
                    $approvals[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_approveLeave($approvals, $changeComments);

                foreach ($approvals as $approval) {
                    $leaveApprovalMailer = new LeaveApprovalMailer(array($approval), $changedByUserType, $changedUserId, 'single');
                    $leaveApprovalMailer->send();
                }

                $rejections = array();
                foreach ($rejectionIds as $leaveId) {
                    $rejections[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_rejectLeave($rejections, $changeComments);

                foreach ($rejections as $rejection) {
                    $leaveRejectionMailer = new LeaveRejectionMailer(array($rejection), $changedByUserType, $changedUserId, 'single');
                    $leaveRejectionMailer->send();
                }

                $cancellations = array();
                foreach ($cancellationIds as $leaveId) {
                    $cancellations[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_cancelLeave($cancellations, $changedByUserType);

                foreach ($cancellations as $cancellation) {

                    if ($changedByUserType == Users::USER_TYPE_EMPLOYEE) {
                        $leaveCancellationMailer = new LeaveEmployeeCancellationMailer(array($cancellation), $changedByUserType, $changedUserId, 'single');
                    } else {
                        $leaveCancellationMailer = new LeaveCancellationMailer(array($cancellation), $changedByUserType, $changedUserId, 'single');
                    }
                    
                    $leaveCancellationMailer->send();
                }

            } else {
                throw new LeaveServiceException('Wrong change type passed');
            }
        }else {
            throw new LeaveServiceException('Empty changes list');
        }

    }

    private function _approveLeave($leave, $comments, $changeType = null) {
        $leaveStateManager = LeaveStateManager::instance();

        $leaveRequests = array();
        foreach ($leave as $approval) {
            $leaveRequestId = $approval->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $approval->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $approval;

            $comment = is_array($comments) ? $comments[$approval->getLeaveId()] : $comments;

            $leaveStateManager->setLeave($approval);
            $leaveStateManager->setChangeComments($comment);
            $leaveStateManager->approve();
        }

    }

    private function _rejectLeave($leave, $comments, $changeType = null) {
        $leaveStateManager = LeaveStateManager::instance();

        $leaveRequests = array();
        foreach ($leave as $rejection) {
            $leaveRequestId = $rejection->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $rejection->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $rejection;

            $comment = is_array($comments) ? $comments[$rejection->getLeaveId()] : $comments;

            $leaveStateManager->setLeave($rejection);
            $leaveStateManager->setChangeComments($comment);
            $leaveStateManager->reject();
        }

    }

    private function _cancelLeave($leave, $changeType = null) {
        $leaveStateManager = LeaveStateManager::instance();

        $leaveRequests = array();
        foreach ($leave as $cancellation) {
            $leaveRequestId = $cancellation->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $cancellation->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $cancellation;

            $leaveStateManager->setLeave($cancellation);
            $leaveStateManager->cancel();
        }

    }

    public function getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->leaveRequestDao->getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId);

    }

    public function getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->leaveRequestDao->getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId);

    }

    /**
     *
     * @param string $element
     * @return boolean
     */
    private function _filterApprovals($element) {
        return ($element == 'markedForApproval');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterRejections($element) {
        return ($element == 'markedForRejection');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterCancellations($element) {
        return ($element == 'markedForCancellation');
    }


}
