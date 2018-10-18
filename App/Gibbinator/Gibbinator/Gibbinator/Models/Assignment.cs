using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public class Assignment
    {
        public int TaskId { get; set; }
        public string Title { get; set; }
        public string Description { get; set; }
        public AssignmentType TaskType { get; set; }
    }
}
