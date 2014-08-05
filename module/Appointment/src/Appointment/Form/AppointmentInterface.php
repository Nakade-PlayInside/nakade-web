<?php
namespace Appointment\Form;


interface AppointmentInterface
{
    const FIELD_CONFIRM_USER = "userConfirm";
    const FIELD_TIME = "time";
    const FIELD_DATE = "date";
    const FIELD_OLD_DATE = "oldDate";
    const FIELD_REJECT_REASON = 'rejectReason';
    const FIELD_CONFIRM_APPOINTMENT = "confirmAppointment";
    const FIELD_REJECT_APPOINTMENT = "rejectAppointment";
}