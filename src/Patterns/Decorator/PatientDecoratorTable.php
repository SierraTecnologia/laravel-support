class PatientDecoratorTable implements IPatient{

private IPatient patient;

public PatientDecoratorTable(IPatient patient) {
    this.patient = patient;
}
public String getId() {
    return "#" + patient.getId();
}
public String getFirstName() {
    return patient.getFirstName();
}
public String getLastName() {
    return patient.getLastName();
}
public Date getDob() {
    return patient.getDob();
}
public String getFullName() {
    return patient.getFirstName() + " " + patient.getLastName();
}
public int getAge() {
    Calendar dob = Calendar.getInstance();
    dob.setTime(patient.getDob());
    Calendar tdy = Calendar.getInstance();
    int age = tdy.get(Calendar.YEAR) - dob.get(Calendar.YEAR);
    if (tdy.get(Calendar.DAY_OF_YEAR) <= dob.get(Calendar.DAY_OF_YEAR))
        age--;
    return age;
}
}