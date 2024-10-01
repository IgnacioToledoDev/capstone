import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RegisterUserPage } from './register-user.page';

describe('RegisterUserPage', () => {
  let component: RegisterUserPage;
  let fixture: ComponentFixture<RegisterUserPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterUserPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
