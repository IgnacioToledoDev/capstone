import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InfoMantePage } from './info-mante.page';

describe('InfoMantePage', () => {
  let component: InfoMantePage;
  let fixture: ComponentFixture<InfoMantePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(InfoMantePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
