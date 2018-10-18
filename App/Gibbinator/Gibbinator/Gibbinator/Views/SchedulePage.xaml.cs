using Syncfusion.SfSchedule.XForms;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class SchedulePage : ContentPage
	{
        public enum ScheduleViews
        {
            WorkWeek,
            Day,
            Month
        }

        ScheduleViews CurrentView = ScheduleViews.WorkWeek;

        public SchedulePage ()
		{
			InitializeComponent ();
		}

        void ChangeView(object sender, EventArgs e)
        {
            switch (CurrentView)
            {
                case ScheduleViews.WorkWeek:
                    Schedule.ScheduleView = ScheduleView.DayView;
                    CurrentView = ScheduleViews.Day;
                    break;
                case ScheduleViews.Day:
                    Schedule.ScheduleView = ScheduleView.MonthView;
                    CurrentView = ScheduleViews.Month;
                    break;
                case ScheduleViews.Month:
                    Schedule.ScheduleView = ScheduleView.WorkWeekView;
                    Schedule.ShowAppointmentsInline = true;
                    CurrentView = ScheduleViews.WorkWeek;
                    break;
            }

        }
    }
}