import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AgendarPage } from './agendar.page';

describe('AgendarPage', () => {
  let component: AgendarPage;
  let fixture: ComponentFixture<AgendarPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(AgendarPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
