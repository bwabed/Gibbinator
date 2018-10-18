using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public enum MenuItemType
    {
        Schedule,
        Upcoming,
        Messages,
        Teachers, 
        Calculator,
        About
    }
    public class HomeMenuItem
    {
        public MenuItemType Id { get; set; }

        public string Title { get; set; }
    }
}
