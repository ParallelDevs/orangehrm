<?php

/**
 * BaseLeave
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $leave_id
 * @property decimal $leave_length_hours
 * @property decimal $leave_length_days
 * @property integer $leave_request_id
 * @property string $leave_type_id
 * @property integer $employee_id
 * @property date $leave_date
 * @property integer $leave_status
 * @property string $leave_comments
 * @property time $start_time
 * @property time $end_time
 * @property LeaveRequest $LeaveRequest
 * 
 * @method integer      getLeaveId()            Returns the current record's "leave_id" value
 * @method decimal      getLeaveLengthHours()   Returns the current record's "leave_length_hours" value
 * @method decimal      getLeaveLengthDays()    Returns the current record's "leave_length_days" value
 * @method integer      getLeaveRequestId()     Returns the current record's "leave_request_id" value
 * @method string       getLeaveTypeId()        Returns the current record's "leave_type_id" value
 * @method integer      getEmployeeId()         Returns the current record's "employee_id" value
 * @method date         getLeaveDate()          Returns the current record's "leave_date" value
 * @method integer      getLeaveStatus()        Returns the current record's "leave_status" value
 * @method string       getLeaveComments()      Returns the current record's "leave_comments" value
 * @method time         getStartTime()          Returns the current record's "start_time" value
 * @method time         getEndTime()            Returns the current record's "end_time" value
 * @method LeaveRequest getLeaveRequest()       Returns the current record's "LeaveRequest" value
 * @method Leave        setLeaveId()            Sets the current record's "leave_id" value
 * @method Leave        setLeaveLengthHours()   Sets the current record's "leave_length_hours" value
 * @method Leave        setLeaveLengthDays()    Sets the current record's "leave_length_days" value
 * @method Leave        setLeaveRequestId()     Sets the current record's "leave_request_id" value
 * @method Leave        setLeaveTypeId()        Sets the current record's "leave_type_id" value
 * @method Leave        setEmployeeId()         Sets the current record's "employee_id" value
 * @method Leave        setLeaveDate()          Sets the current record's "leave_date" value
 * @method Leave        setLeaveStatus()        Sets the current record's "leave_status" value
 * @method Leave        setLeaveComments()      Sets the current record's "leave_comments" value
 * @method Leave        setStartTime()          Sets the current record's "start_time" value
 * @method Leave        setEndTime()            Sets the current record's "end_time" value
 * @method Leave        setLeaveRequest()       Sets the current record's "LeaveRequest" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseLeave extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_leave');
        $this->hasColumn('leave_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('leave_length_hours', 'decimal', 6, array(
             'type' => 'decimal',
             'unsigned' => 1,
             'length' => '6',
             'scale' => ' unsigned',
             ));
        $this->hasColumn('leave_length_days', 'decimal', 4, array(
             'type' => 'decimal',
             'unsigned' => 1,
             'length' => '4',
             'scale' => ' unsigned',
             ));
        $this->hasColumn('leave_request_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('leave_type_id', 'string', 13, array(
             'type' => 'string',
             'primary' => true,
             'length' => 13,
             ));
        $this->hasColumn('employee_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('leave_date', 'date', 25, array(
             'type' => 'date',
             'length' => 25,
             ));
        $this->hasColumn('leave_status', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('leave_comments', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('start_time', 'time', 25, array(
             'type' => 'time',
             'length' => 25,
             ));
        $this->hasColumn('end_time', 'time', 25, array(
             'type' => 'time',
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('LeaveRequest', array(
             'local' => 'leave_request_id',
             'foreign' => 'leave_request_id'));
    }
}