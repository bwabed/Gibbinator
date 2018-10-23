package md56a462996b33a3e9d22f9d04f97ec7ebe;


public class HorizontalLinearLayout
	extends android.widget.LinearLayout
	implements
		mono.android.IGCUserPeer
{
/** @hide */
	public static final String __md_methods;
	static {
		__md_methods = 
			"";
		mono.android.Runtime.register ("Com.Syncfusion.Schedule.HorizontalLinearLayout, Syncfusion.SfSchedule.Android", HorizontalLinearLayout.class, __md_methods);
	}


	public HorizontalLinearLayout (android.content.Context p0)
	{
		super (p0);
		if (getClass () == HorizontalLinearLayout.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.HorizontalLinearLayout, Syncfusion.SfSchedule.Android", "Android.Content.Context, Mono.Android", this, new java.lang.Object[] { p0 });
	}


	public HorizontalLinearLayout (android.content.Context p0, android.util.AttributeSet p1)
	{
		super (p0, p1);
		if (getClass () == HorizontalLinearLayout.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.HorizontalLinearLayout, Syncfusion.SfSchedule.Android", "Android.Content.Context, Mono.Android:Android.Util.IAttributeSet, Mono.Android", this, new java.lang.Object[] { p0, p1 });
	}


	public HorizontalLinearLayout (android.content.Context p0, android.util.AttributeSet p1, int p2)
	{
		super (p0, p1, p2);
		if (getClass () == HorizontalLinearLayout.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.HorizontalLinearLayout, Syncfusion.SfSchedule.Android", "Android.Content.Context, Mono.Android:Android.Util.IAttributeSet, Mono.Android:System.Int32, mscorlib", this, new java.lang.Object[] { p0, p1, p2 });
	}


	public HorizontalLinearLayout (android.content.Context p0, android.util.AttributeSet p1, int p2, int p3)
	{
		super (p0, p1, p2, p3);
		if (getClass () == HorizontalLinearLayout.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.HorizontalLinearLayout, Syncfusion.SfSchedule.Android", "Android.Content.Context, Mono.Android:Android.Util.IAttributeSet, Mono.Android:System.Int32, mscorlib:System.Int32, mscorlib", this, new java.lang.Object[] { p0, p1, p2, p3 });
	}

	private java.util.ArrayList refList;
	public void monodroidAddReference (java.lang.Object obj)
	{
		if (refList == null)
			refList = new java.util.ArrayList ();
		refList.add (obj);
	}

	public void monodroidClearReferences ()
	{
		if (refList != null)
			refList.clear ();
	}
}
